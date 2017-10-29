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
            
            return view('volunteers.volunteer')
            -> with('volunteers', $volunteers);
        }
        
        return view('volunteers.volunteer')
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
        

        return view('volunteers.create_volunteer')
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
            'name' => 'required|min:2|max:80|regex:/^[a-zA-ZÑñ\s]+$/',
        ];

        try {
            
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                if(file_exists(public_path() . '/images/volunteers/' . $request -> image)){
                    Storage::delete(public_path() . '/images/volunteers/' . $request -> image);
                }
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
                if(file_exists(public_path() . '/images/volunteers/' . $request -> image)){
                    Storage::delete(public_path() . '/images/volunteers/' . $request -> image);
                }

                flash('This volunteer already exists.') -> error();
                return redirect() -> route('volunteers.create')
                -> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events);
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
            if(file_exists(public_path() . '/images/volunteers/' . $request -> image)){
                Storage::delete(public_path() . '/images/volunteers/' . $request -> image);
            }
            
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
            
            return view('volunteers.show_volunteer')
            -> with('volunteer', $volunteer);
        }

        $my_shows  = $volunteer -> shows -> pluck('name')->all();
        $my_events = $volunteer -> events -> pluck('name')->all();
        $volunteer -> sponsor;
        
        return view('volunteers.show_volunteer')
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
            
            return view('volunteers.edit_volunteer')
            -> with('volunteer', $volunteer);
        }

        $my_shows  = $volunteer -> shows -> pluck('id')->all();
        $my_events = $volunteer -> events -> pluck('id')->all();
        $volunteer -> sponsor;

        $events = Event::orderBy('name','DESC') -> pluck('name','id');
        $shows  = Show::orderBy('name','DESC') -> pluck('name','id');
        
        return view('volunteers.edit_volunteer')
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

       if(!$request -> name && !$request -> status && !$request -> description && !$request -> image_name
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
                        if(empty($show) && $request -> image_name){
                            if(file_exists(public_path() . '/images/volunteers/' . $request -> image_name)){
                                Storage::delete(public_path() . '/images/volunteers/' . $request -> image_name);
                            }

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
                        if(empty($event) && $request -> image_name){
                            if(file_exists(public_path() . '/images/volunteers/' . $request -> image_name)){
                                Storage::delete(public_path() . '/images/volunteers/' . $request -> image_name);
                            }
                            
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

                if($request -> image_name){
                    if(file_exists(public_path() . '/images/volunteers/' . $volunteer -> image)){
                        Storage::delete(public_path() . '/images/volunteers/' . $volunteer -> image);
                    }
                    $update['image'] = $request -> image_name;
                }

                $volunteer -> update($update);
            }
            catch(QueryException $e){
                \Log::error('Error updating show: '.$e);
                if($request -> image_name){
                    if(file_exists(public_path() . '/images/volunteers/' . $request -> image_name)){
                        Storage::delete(public_path() . '/images/volunteers/' . $request -> image_name);
                    }
                }
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
        if(file_exists(public_path() . '/images/volunteers/' . $volunteer -> image)){
            Storage::delete(public_path() . '/images/volunteers/' . $volunteer -> image);
        }
        $volunteer -> delete();

        flash('The volunteer has been deleted correctly.') -> success();
        return redirect() -> route('dashboard')
        -> with('user', $user -> name) 
        -> with('sales', $this -> sales)
        -> with('events', $this -> events);
    }
}
