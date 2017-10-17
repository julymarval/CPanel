<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Show;
use App\Volunteer;
use App\Event;
use App\Sale;
use Auth;
use Config;

class ShowsController extends Controller
{

    private $sales, $events;

    /**
    * Constructor
    * It starts the jwt token validator before accessing to any function
    * @param none
    * @return none
    */

    public function __construct() {
        
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        //$this->middleware('jwt.auth',['except' => ['index', 'show']]);
        $this->middleware('auth',['except' => ['index', 'show']]);
        $this -> events = Event::orderBy(Config::get('constants.fields.IdField'),'DESC')->paginate(5);
        $this -> sales = Sale::orderBy(Config::get('constants.fields.IdField'),'DESC')->paginate(5);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shows = Show::orderBy(Config::get('constants.fields.IdField'),'DESC')->paginate(5);
        
        if(empty($shows)){
            $code = Config::get('constants.codes.NonExistingShowsCode'); 
            $msg = Config::get('constants.msgs.NonExistingShowsMsg');
            
            return view('shows.show') 
            -> with('shows', $shows)
            -> with('code', $code)
            -> with('msg', $msg);
        }

        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');
        
        return view('shows.show')
        -> with('code', $code)
        -> with('msg', $msg)
        -> with('shows', $shows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $volunteers = Volunteer::orderBy('name','DESC') -> pluck('name','id')->all();

        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');
                
        return view('shows.create_show')
        -> with('code', $code)
        -> with('msg', $msg)
        -> with('volunteers', $volunteers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (empty($request -> name)) {
            
            flash('Name is required') -> error();
            return redirect() -> route('shows.create') 
            -> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events);
        }
        
        $rules = [
            'name'     => 'required|min:2|max:80|unique:shows|regex:/^[a-zA-ZÑñ\s]+$/',
            'schedule' => 'required'
        ];

        try {
            
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                
                flash('One or more value are wrong.') -> error();
                return redirect() -> route('shows.create') 
                -> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events);
            }

            $show = new Show($request->all());

            $data = DB::table(Config::get('constants.tables.ShowsTable'))
                ->where(Config::get('constants.fields.NameField'), $show->name)->first();

            if(!empty($data)){
                flash('This show already exists.') -> error();
                return redirect() -> route('shows.create') 
                -> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events);
            }

            if($request->file('image')){
                $file = $request -> file('image');
                $name = $request -> name . '.' . $file->getClientOriginalExtension();
                $path = public_path() . '/images/shows/';
                $file -> move($path,$name);
                $show -> image = $name;
            }


            $show -> save();

            if($request -> volunteer_id){
                foreach($request -> volunteer_id as $id){
                    $volunteer = Volunteer::find($id);
                    if(empty($volunteer)){
                        flash('This volunteer doesnt exists. Please update the event and add a valid volunteer.') -> error();
                        return redirect() -> route('shows.index') 
                        -> with('user', $user -> name) 
                        -> with('sales', $this -> sales)
                        -> with('events', $this -> events);
                    }
                }
                foreach ($request -> volunteer_id as $id){
                    $show -> volunteers() -> attach($id);
                }
            }

            flash('The show has been created correctly.') -> success();
            return redirect() -> route('dashboard') 
            -> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events);
            
        } catch (Exception $e) {
            \Log::info('Error creating sale: '.$e);
            
            flash('Ops! An error has ocurred. Please try again.') -> error();
            return redirect() -> route('dashboard') 
            -> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show = Show::find($id);
    
        if(empty($show)){
            $code = Config::get('constants.codes.NonExistingShowsCode');
            $msg = Config::get('constants.msgs.NonExistingShowsMsg');
            
            return view('shows.show_show') 
            -> with('code', $code)
            -> with('show', $show)
            -> with('msg', $msg);
        }

        $my_volunteers = $show -> volunteers -> pluck('name')->all();
        $path = public_path() . '/images/shows/';

        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');

        return view('shows.show_show') 
        -> with('code', $code)
        -> with('msg', $msg)
        -> with('show', $show)
        -> with('volunteers', $my_volunteers)
        -> with('path', $path);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $show = Show::find($id);
        
        if(empty($show)){
            $code = Config::get('constants.codes.NonExistingEventCode'); 
            $msg = Config::get('constants.msgs.NonExistingEventMsg');

            return view('shows.edit_show') 
            -> with('show', $show)
            -> with('code', $code)
            -> with('msg', $msg);
        }

        $my_volunteers = $show -> volunteers -> pluck('name','id')->all();

        $volunteers = Volunteer::orderBy('name','DESC') -> pluck('name','id')->all();

        $code = Config::get('constants.codes.OkCode');
        $msg = Config::get('constants.msgs.OkMsg');
        
        return view('shows.edit_show') 
        -> with('code', $code)
        -> with('msg', $msg)
        -> with('show', $show)
        -> with('my_volunteers', $my_volunteers)
        -> with('volunteers',$volunteers);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if(!$request -> name && !$request -> schedule && !$request -> description && !$request->file('image')
            && !$request -> volunteer_id){
                
                flash('At least one field is required.') -> error();
                return redirect() -> route('shows.edit',['id' => $id]) 
                -> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events);
        }
            
        else{
            $show = Show::find($id);

            $update = array();
            try{
                if(!empty($request -> volunteer_id)){
                    foreach($request -> volunteer_id as $id){
                        $volunteer = Volunteer::find($id);
                        if(empty($volunteer)){
                            flash('This volunteer doesnt exists. Please update the event and add a valid volunteer.') -> error();
                            return redirect() -> route('dashboard') 
                            -> with('user', $user -> name) 
                            -> with('sales', $this -> sales)
                            -> with('events', $this -> events);
                        }
                    }
                    foreach($request -> volunteer_id as $id){
                        $show -> volunteers() -> attach($id);
                    }
                }
                
                if(!empty($request -> name)){
                    $update['name'] = $request -> name;
                }
                
                if(!empty($request -> schedule)){
                    $update['schedule'] =  $request -> schedule;
                }
                
                if(!empty($request -> description)){
                    $update['description'] =  $request -> description;
                }

                if(!empty($request->file('image'))){
                    $file = $request -> file('image');
                    $name = $show -> name . '.' . $file->getClientOriginalExtension();
                    if(file_exists(public_path() . '/images/shows/' . $show -> image)){
                        Storage::delete(public_path() . '/images/shows/' . $show -> image);
                    }
                    $path = public_path() . '/images/shows/';
                    $file -> move($path,$name);
                    $update['image'] = $name;
                }

                $show -> update($update);
            }
            catch(QueryException $e){
                \Log::error('Error updating show: '.$e);
                
                flash('Ops! An error has ocurred. Please try again.') -> error();
                return redirect() -> route('shows.index') 
                -> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events);
            }
        
            flash('The show has been updated correctly.') -> success();
            return redirect() -> route('dashboard') 
            -> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $show = Show::find($id);
        $show -> delete();

        flash('The show has been deleted correctly.') -> success();
        return redirect() -> route('dashboard')
        -> with('user', $user -> name) 
        -> with('sales', $this -> sales)
        -> with('events', $this -> events);
    }
}
