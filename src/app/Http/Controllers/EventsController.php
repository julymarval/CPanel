<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Auth;
use Config;
use App\Event;
use App\Image;
use App\Sale;
use App\Sponsor;
use App\Volunteer;

class EventsController extends Controller
{

    private $sales, $events, $sponsors, $image;

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
        $this -> sponsors = Sponsor::orderBy(Config::get('constants.fields.IdField'),'DESC')->get();
        
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $events = Event::select('id','name','date','description')->orderBy(Config::get('constants.fields.IdField'),'DESC')->paginate(5);
        
        $k = 0;

        $now = date('Y-m-d');
        $futureevents = DB::table('events') -> whereDate('date', '>=', $now)-> paginate(5);
        $pastevents = DB::table('events') -> whereDate('date', '<', $now)-> paginate(5);
        
        if(!empty($events)){
            foreach($events as $event){
                $img = Image::select('id','name')->where('event_id', $event -> id)-> first();
                if(!empty($img)){
                    $this -> image[$k] = $img;
                }
                $k = $k +1;
            }
        }
        
        $images = Image::select('id','name','event_id')->get();
        
        return view('events.event')
        -> with('sponsors', $this -> sponsors)
        -> with('images', $this -> image)
        -> with('imgs', $images)
        -> with('pastevents', $pastevents)
        -> with('futureevents' , $futureevents)
        -> with('events', $events);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sponsors   = Sponsor::orderBy('name','DESC') -> pluck('name', 'id') -> all();;
        $volunteers = Volunteer::orderBy('name','DESC') -> pluck('name', 'id') -> all();

        return view('events.create_event')
        -> with ('sponsors', $sponsors)
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
        
        if (!$request -> name || !$request -> date) {
                        
            flash('Name and Date are required') -> error();
            return redirect() -> route('events.create')
            -> with('user', $user -> name)
            -> with('sales', $this -> sales)
            -> with('events', $this -> events);
        }
        
        $rules = [
            'name' => 'required|min:2|max:80|regex:/^[a-zA-ZÑñ\s]+$/',
            'date' => 'required|date_format:Y-m-d',
        ];

        try {
            
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                
                flash('One or more value are wrong.') -> error();
                return redirect() -> route('events.create')
                -> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events);
            }

            $event = new Event($request->all());

            $data = DB::table(Config::get('constants.tables.EventsTable'))
                ->where(Config::get('constants.fields.NameField'), $event -> name)->first();

            if(!empty($data)){

                $images = Image::select('id','name')->where('event_id', $data -> id)->get();
                foreach($images as $image){
                    $path = public_path() . '/images/events/' . $image -> name;
                    \Storage::delete($path);
                    $image -> delete();
                }
                
                flash('This event already exists.') -> error();
                return redirect() -> route('events.create')
                -> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events);

            }

            $event -> save();
            
            if($request -> file_ids){
                Image::whereIn('id', explode(",", $request -> file_ids))
                ->update(['event_id' => $event -> id]);
            }

            if($request -> volunteer_id){      
                foreach($request -> volunteer_id as $id){
                    $volunteer = Volunteer::find($id);
                    if(empty($volunteer)){
                        flash('This volunteer doesnt exists. Please update the event and add a valid volunteer.') -> error();
                        return redirect() -> route('events.index')
                        -> with('user', $user -> name) 
                        -> with('sales', $this -> sales)
                        -> with('events', $this -> events);
                    }
                }

                foreach($request -> volunteer_id as $id){
                    $event -> volunteers() -> attach($id);
                }
            }

            if($request -> sponsor_id){
                foreach($request -> sponsor_id as $id){
                    $sponsor = Sponsor::find($id);
                    
                    if(empty($sponsor)){
                        
                        flash('This sponsor doesnt exists. Please update the event and add a valid sponsor.') -> error();
                        return redirect() -> route('dashboard')
                        -> with('user', $user -> name) 
                        -> with('sales', $this -> sales)
                        -> with('events', $this -> events);
                    }
                }
                foreach($request -> sponsor_id as $id){
                    $event -> sponsors() -> attach($id);
                }
            }

        } catch (Exception $e) {
            \Log::info('Error creating event: '.$e);

            $images = Image::select('id','name')->where('event_id', $id)->get();
            foreach($images as $image){
                $path = public_path() . '/images/events/' . $image -> name;
                \Storage::delete($path);
                $image -> delete();
            }
            
            flash('Ops! An error has ocurred. Please try again.') -> error();
            return redirect() -> route('events.index')
            -> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events);

        }

        flash('The event has been created correctly.') -> success();
        return redirect() -> route('dashboard')
        -> with('user', $user -> name) 
        -> with('sales', $this -> sales)
        -> with('events', $this -> events);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::find($id);

        if(empty($event)){
                            
            return view('events.show_event')
            > with('event', $event);
        }

        $my_sponsors   = $event -> sponsors   -> pluck('name')->all();
        $my_volunteers = $event -> volunteers -> pluck('name')->all();
        $images = Image::select('id','name')->where('event_id', $id)->get();
        
        return view('events.show_event')
        -> with('event', $event)
        -> with('my_sponsors', $my_sponsors)
        -> with('my_volunteers', $my_volunteers)
        -> with('images', $images);

        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::find($id);

        if(empty($event)){
            
            return view('events.edit_event') 
            > with('event', $event);
        }

        $my_volunteers = $event -> volunteers -> pluck('id', 'name')->all();        
        $my_sponsors   = $event -> sponsors   -> pluck('id', 'name')->all();
        $volunteers = Volunteer::orderBy('name','DESC') -> pluck('name','id')->all();
        $sponsors = Sponsor::orderBy('name','DESC') -> pluck('name','id')->all();

        return view('events.edit_event')
        -> with('event', $event)
        -> with('volunteer', $my_volunteers)
        -> with('sponsor', $my_sponsors)
        -> with('volunteers', $volunteers)
        -> with('sponsors', $sponsors);

        
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

        if (!$request -> name && !$request -> date && !$request -> description
            && !$request -> volunteer_id && !$request -> sponsor_id && !$request -> file_ids) {
        
            flash('At least one field is required.') -> error();
            return redirect() -> route('events.edit',['id' => $id]) 
            -> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events);
        }

        else{
            $event = Event::find($id);

            if($request -> file_ids){
                Image::whereIn('id', explode(",", $request -> file_ids))
                ->update(['event_id' => $event -> id]);
            }

            $update = array();
            try{
                if(!empty($request -> volunteer_id)){
                    foreach($request -> volunteer_id as $id){
                        $volunteer = Volunteer::find($id);
                        
                        if(empty($volunteer)){
                            flash('This volunteer doesnt exists. Please update the event and add a valid volunteer.') -> error();
                            return redirect() -> route('events.index')
                            -> with('user', $user -> name) 
                            -> with('sales', $this -> sales)
                            -> with('events', $this -> events);
                        }
                    }
                    foreach($request -> volunteer_id as $id){
                        $event -> volunteers() -> attach($id);
                    }
                }

                if(!empty($request -> sponsor_id)){
                    foreach($request -> sponsor_id as $id){
                        $sponsor = Sponsor::find($id);
                        if(empty($sponsor)){
                            flash('This sponsor doesnt exists. Please update the event and add a valid sponsor.') -> error();
                            return redirect() -> route('events.index') 
                            -> with('user', $user -> name) 
                            -> with('sales', $this -> sales)
                            -> with('events', $this -> events);
                        }
                    }
                    foreach($request -> sponsor_id as $id){
                        $event -> sponsors() -> attach($id);
                    }
                }

                if(!empty($request -> name)){
                    $update['name'] = $request -> name;
                    $rules = [
                        'name' => 'min:2|max:80|unique:events',
                    ];
                               
                    $validator = \Validator::make($request -> name, $rules);
                    if ($validator->fails()) {
                        
                        flash('Invalid name format. Please enter a name with letters') -> error();
                        return redirect() -> route('events.edit',['id' => $id]) 
                        -> with('user', $user -> name) 
                        -> with('sales', $this -> sales)
                        -> with('events', $this -> events);
                    }
    
                }
                
                if(!empty($request -> date)){
                    $update['date'] =  $request -> date;

                    $rules = [
                        'date' => 'date_format:Y-m-d'
                    ];
                        
                    $validator = \Validator::make($request->all(), $rules);
                    if ($validator->fails()) {
                        
                        flash('Invalid date format. Please enter a valid date.') -> error();
                        return redirect() -> route('events.edit',['id' => $id]) 
                        -> with('user', $user -> name) 
                        -> with('sales', $this -> sales)
                        -> with('events', $this -> events);
                    }
    
                }
                
                if(!empty($request -> description)){
                    $update['description'] =  $request -> description;
                }

                $event -> update($update);

            }
            catch(QueryException $e){
                
                \Log::error('Error creating sale: '.$e);
                
                flash('Ops! An error has ocurred. Please try again.') -> error();
                return redirect() -> route('events.index') 
                -> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events);
            }
        
            flash('The event has been updated correctly.') -> success();
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
        
        $event = Event::find($id);

        $images = Image::select('id','name')->where('event_id', $id)->get();
        foreach($images as $image){
            $path = public_path() . '/images/events/' . $image -> name;
            \Storage::delete($path);
            $image -> delete();
        } 
        
        $event -> delete();
        
        flash('The event has been deleted correctly.') -> success();
        return redirect() -> route('dashboard')
        -> with('user', $user -> name) 
        -> with('sales', $this -> sales)
        -> with('events', $this -> events);
    }
}
