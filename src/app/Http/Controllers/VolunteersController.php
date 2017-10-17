<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Volunteer;
use App\Show;
use App\Event;
use App\Sale;
use App\Sponsor;
use Config;

class VolunteersController extends Controller
{

    private $events, $sales;
    
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
        $volunteers = Volunteer::orderBy(Config::get('constants.fields.IdField'),'DESC')->paginate(5);
        
        if(empty($volunteers)){
            $code = Config::get('constants.codes.NonExistingVolunteerCode'); 
            $msg = Config::get('constants.msgs.NonExistingVolunteerMsg');

            return view('volunteers.volunteer')
            -> with('volunteers', $volunteers)
            -> with('code', $code)
            -> with('msg', $msg);
        }
        
        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');

        return view('volunteers.volunteer')
        -> with('code', $code)
        -> with('msg', $msg)
        -> with('volunteers', $volunteers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $events = Event::orderBy('name','DESC') -> pluck('name', 'id') -> all();
        $shows = Show::orderBy('name','DESC') -> pluck('name', 'id') -> all();
        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');

        return view('volunteers.create_volunteer')
        -> with('code', $code)
        -> with('msg', $msg)
        -> with('events', $events)
        -> with('shows', $shows);
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

        if (!$request -> name) {
            flash('Name is required') -> error();
            return redirect() -> route('volunteers.create')
            -> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events);
        }
        
        $rules = [
            'name'     => 'required|min:2|max:80|unique:volunteers|regex:/^[a-zA-ZÑñ\s]+$/',
        ];

        try {
            
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                flash('One or more value are wrong.') -> error();
                return redirect() -> route('volunteers.create')
                -> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events);
            }

            $volunteer = new Volunteer($request->all());

            $data = DB::table(Config::get('constants.tables.VolunteersTable'))
                ->where(Config::get('constants.fields.NameField'), $volunteer -> name)->first();

            if(!empty($data)){
                flash('This volunteer already exists.') -> error();
                return redirect() -> route('volunteers.create')
                -> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events);
            }

            if($request->file('image')){
                $file = $request -> file('image');
                $name = $request -> name . '.' . $file->getClientOriginalExtension();
                $path = public_path() . '/images/volunteers/';
                $file -> move($path,$name);
                $volunteer -> image = $name;
            }

            $volunteer -> save();

            if($request -> show_id){
                foreach($request -> show_id as $id){
                    $show = Show::find($id);
                    if(empty($show)){
                        flash('This show doesnt exists. Please update the volunteer and add a valid show.') -> error();
                        return redirect() -> route('volunteers.index')
                        -> with('user', $user -> name) 
                        -> with('sales', $this -> sales)
                        -> with('events', $this -> events);
                    }
                }
                foreach($request -> show_id as $id){
                    $volunteer -> shows() -> attach($id);
                }
            }

            if($request -> event_id){
                foreach($request -> event_id as $id){
                    $event = Event::find($id);
                    if(empty($event)){
                        flash('This event doesnt exists. Please update the volunteer and add a valid event.') -> error();
                        return redirect() -> route('volunteers.index')
                        -> with('user', $user -> name) 
                        -> with('sales', $this -> sales)
                        -> with('events', $this -> events);
                    }
                }
                foreach($request -> event_id as $id){
                    $volunteer -> events() -> attach($id);
                }
            }
            
            flash('The volunteer has been created correctly.') -> success();
            return redirect() -> route('dashboard')
            -> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events);
            
        } catch (Exception $e) {
            \Log::info('Error creating sale: '.$e);
            
            flash('Ops! An error has ocurred. Please try again.') -> error();
            return redirect() -> route('volunteers.index')
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
        $volunteer = Volunteer::find($id);
    
        if(empty($volunteer)){
            $code = Config::get('constants.codes.NonExistingVolunteerCode'); 
            $msg = Config::get('constants.msgs.NonExistingVolunteerMsg');

            return view('volunteers.show_volunteer')
            -> with('volunteer', $volunteer)
            -> with('code', $code)
            -> with('msg', $msg);
        }

        $my_shows  = $volunteer -> shows -> pluck('name')->all();
        $my_events = $volunteer -> events -> pluck('name')->all();
        $volunteer -> sponsor;

        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');
        
        return view('volunteers.show_volunteer')
        -> with('code', $code)
        -> with('msg', $msg)
        -> with('volunteer', $volunteer)
        -> with('my_shows', $my_shows)
        -> with('my_events', $my_events);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $volunteer = Volunteer::find($id);
        
        if(empty($volunteer)){
            $code = Config::get('constants.codes.NonExistingEventCode');
            $msg = Config::get('constants.msgs.NonExistingEventMsg');

            return view('volunteers.edit_volunteer')
            -> with('volunteer', $volunteer)
            -> with('code', $code)
            -> with('msg', $msg);
        }

        $my_shows  = $volunteer -> shows -> pluck('id')->all();
        $my_events = $volunteer -> events -> pluck('id')->all();
        $volunteer -> sponsor;

        $events = Event::orderBy('name','DESC') -> pluck('name','id');
        $shows  = Show::orderBy('name','DESC') -> pluck('name','id');
        
        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');

        return view('volunteers.edit_volunteer')
        -> with('code', $code)
        -> with('msg', $msg)
        -> with('volunteer', $volunteer)
        -> with('my_shows', $my_shows)
        -> with('my_events', $my_events)
        -> with('events',$events)
        -> with('shows',$shows);
        
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

        if(!$request -> name && !$request -> status && !$request -> description && !$request->file('image')
            && !$request -> show_id && !$request -> event_id){
            flash('At least one field is requiered.') -> error();
            return redirect() -> route('volunteers.edit', ['id' => $id])
            -> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events);
        }

        else{
            $volunteer = Volunteer::find($id);

            $update = array();
            try{
                if($request -> show_id){
                    foreach($request -> show_id as $id){
                        $show = Show::find($id);
                        if(empty($show)){
                            flash('This show doesnt exists. Please update the volunteer and add a valid show.') -> error();
                            return redirect() -> route('volunteers.index')
                            -> with('user', $user -> name) 
                            -> with('sales', $this -> sales)
                            -> with('events', $this -> events);
                        }
                    }
                    foreach($request -> show_id as $id){
                        $volunteer -> shows() -> attach($id);
                    }
                }

                if($request -> event_id){
                    foreach($request -> event_id as $id){
                        $event = Event::find($id);
                        if(empty($event)){
                            flash('This event doesnt exists. Please update the volunteer and add a valid event.') -> error();
                            return redirect() -> route('volunteers.index')
                            -> with('user', $user -> name) 
                            -> with('sales', $this -> sales)
                            -> with('events', $this -> events);
                        }
                    }
                    foreach($request -> event_id as $id){
                        $volunteer -> events() -> attach($id);
                    }
                }
                
                if(!empty($request -> name)){
                    $update['name'] = $request -> name;
                }
                
                if(!empty($request -> status)){
                    $update['status'] =  $request -> status;
                }
                
                if(!empty($request -> description)){
                    $update['description'] =  $request -> description;
                }

                if(!empty($request -> phone)){
                    $update['phone'] = $request -> phone;
                }

                if(!empty($request -> file('image'))){
                    $file = $request -> file('image');
                    $name = $volunteer -> name . '.' . $file->getClientOriginalExtension();
                    if(file_exists(public_path() . '/images/volunteers/' . $volunteer -> image)){
                        Storage::delete(public_path() . '/images/volunteers/' . $volunteer -> image);
                    }
                    $path = public_path() . '/images/volunteers/';
                    $file -> move($path,$name);
                    $update['image'] = $name;

                }

                $volunteer -> update($update);
            }
            catch(QueryException $e){
                \Log::error('Error updating show: '.$e);
                
                flash('Ops! An error has ocurred. Please try again.') -> error();
                return redirect() -> route('volunteers.index')
                -> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events);
            }
        
            flash('The volunteer has been updated correctly.') -> success();
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

        $volunteer = Volunteer::find($id);
        $volunteer -> delete();

        flash('The volunteer has been deleted correctly.') -> success();
        return redirect() -> route('dashboard')
        -> with('user', $user -> name) 
        -> with('sales', $this -> sales)
        -> with('events', $this -> events);
    }
}
