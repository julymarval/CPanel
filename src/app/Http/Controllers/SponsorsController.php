<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Auth;
use Config;
use App\Sponsor;
use App\Sale;
use App\Event;
use App\Volunteer;

class SponsorsController extends Controller
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
        $sponsors = Sponsor::orderBy(Config::get('constants.fields.IdField'),'DESC') -> paginate(5);

        if(empty($sponsors)){

            return view('sponsors.sponsor') 
            -> with('sponsors', $sponsors);
        }
        
        return view('sponsors.sponsor') 
        -> with('sponsors', $sponsors);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $events   = Event::orderBy('name','DESC') -> pluck('name', 'id') -> all();
        $volunteers = Volunteer::orderBy('name','DESC') -> pluck('name', 'id') -> all();

        return view('sponsors.create_sponsor') 
        -> with ('events', $events)
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

        if (!$request -> name && !$request -> status && !$request -> level) {
            if(file_exists(public_path() . '/images/sponsors/' . $resquest -> image)){
                Storage::delete(public_path() . '/images/sponsors/' . $request -> image);
            }
            flash('Name,Status and Level are required') -> error();
            return redirect() -> route('sponsors.create') 
            -> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events);
        }
        
        $rules = [
            'name'   => 'required|min:2|max:80',
            'status' => 'required',
            'level'  => 'required'
        ];

        try {
            
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                if(file_exists(public_path() . '/images/sponsors/' . $resquest -> image)){
                    Storage::delete(public_path() . '/images/sponsors/' . $request -> image);
                }
                flash('One or more value are wrong.') -> error();
                return redirect() -> route('sponsors.create')
                -> with('user', $user -> name)  
                -> with('sales', $this -> sales)
                -> with('events', $this -> events);
            }

            $sponsor = new Sponsor($request->all());

            $data = DB::table(Config::get('constants.tables.SponsorsTable'))
                ->where(Config::get('constants.fields.NameField'), $sponsor -> name)->first();

            if(!empty($data)){
                if(file_exists(public_path() . '/images/sponsors/' . $resquest -> image)){
                    Storage::delete(public_path() . '/images/sponsors/' . $request -> image);
                }
                flash('This sponsor already exists.') -> error();
                return redirect() -> route('sponsors.create') 
                -> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events);
            }

            $sponsor -> save();

            if($request -> event_id){
                foreach($request -> event_id as $id){
                    $event = Event::find($id);
                    if(empty($event)){
                        flash('This event doesnt exists. Please update the sponsor and add a valid event.') -> error();
                        return redirect() -> route('sponsors.index') 
                        -> with('user', $user -> name) 
                        -> with('sales', $this -> sales)
                        -> with('events', $this -> events);
                    }
                }
                foreach($request -> event_id as $id){
                    $sponsor -> events() -> attach($request -> event_id);
                }
            }

            flash('The sponsor has been created correctly.') -> success();
            return redirect() -> route('dashboard') 
            -> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events);
            
        } catch (Exception $e) {
            \Log::info('Error creating sale: '.$e);
            if(file_exists(public_path() . '/images/sponsors/' . $resquest -> image)){
                Storage::delete(public_path() . '/images/sponsors/' . $request -> image);
            }

            flash('Ops! An error has ocurred. Please try again.') -> error();
            return redirect() -> route('sponsors.index') 
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
        
        $sponsor = Sponsor::find($id);
        
        if(empty($sponsor)){
    
            return view('sponsors.show_sponsor') 
            -> with('sponsor', $sponsor);
        }

        $my_events = $sponsor -> events -> pluck('name') -> all();
        $my_volunteer = Volunteer::find($sponsor -> volunteer_id);
        
        return view('sponsors.show_sponsor') 
        -> with('sponsor', $sponsor)
        -> with('my_events', $my_events)
        -> with('volunteer', $my_volunteer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sponsor = Sponsor::find($id);
        
        if(empty($sponsor)){

            return view('sponsors.edit_sponsor') 
            -> with('sponsor', $sponsor);
        }

        $my_volunteer =  Volunteer::find($sponsor -> volunteer_id);
        $my_events    = $sponsor -> events -> pluck('id','name') -> all();

        $volunteers = Volunteer::orderBy('name','DESC') -> pluck('name', 'id') -> all();
        $events     = Event::orderBy('name','DESC') -> pluck('name', 'id');

        return view('sponsors.edit_sponsor')
        -> with('sponsor', $sponsor)
        -> with('my_volunteers', $my_volunteer)
        -> with('my_events',$my_events)
        -> with('volunteers',$volunteers)
        -> with('events', $events);
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

        if(!$request -> name && !$request -> status && !$request -> description && !$request -> event_id 
            && !$request -> image_name && !$request -> volunteer_id && !$request -> level && !$request -> link ){
                
                if(file_exists(public_path() . '/images/sponsors/' . $resquest -> image_name)){
                    Storage::delete(public_path() . '/images/sponsors/' . $request -> image_name);
                }   
                flash('At least one field is required.') -> error();
                return redirect() -> route('sponsors.edit',['id' => $id])
                -> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events);
        }
            
        else{
            $sponsor = Sponsor::find($id);

            $update = array();
            try{
                if(!empty($request -> volunteer_id)){
                    $volunteer = Volunteer::find($request -> volunteer_id);
                    
                    if(empty($volunteer)){
                        if(file_exists(public_path() . '/images/sponsors/' . $sponsor -> image)){
                            Storage::delete(public_path() . '/images/sponsors/' . $sponsor -> image);
                        }
                        
                        flash('This volunteer doesnt exists. Please update the sponsor and add a valid volunteer.') -> error();
                        return redirect() -> route('sponsors.index')
                        -> with('user', $user -> name) 
                        -> with('sales', $this -> sales)
                        -> with('events', $this -> events);
                    }
                    $update['volunteer_id'] = $request -> volunteer_id;
                }

                if(!empty($request -> event_id)){
                    foreach($request -> event_id as $id){
                        $event = Event::find($id);
                        if(empty($event)){
                            flash('This event doesnt exists. Please update the sponsor and add a valid event.') -> error();
                            return redirect() -> route('sponsors.index')
                            -> with('user', $user -> name) 
                            -> with('sales', $this -> sales)
                            -> with('events', $this -> events);
                        }
                    }
                    foreach($request -> event_id as $id){
                        $sponsor -> events() -> attach($id);
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

                if(!empty($request -> level)){
                    $update['level'] =  $request -> level;
                }

                if(!empty($request -> address)){
                    $update['address'] =  $request -> address;
                }

                if(!empty($request -> link)){
                    $update['link'] =  $request -> link;
                }

                if($request -> image_name){
                    if(file_exists(public_path() . '/images/sponsors/' . $sponsor -> image)){
                        Storage::delete(public_path() . '/images/sponsors/' . $sponsor -> image);
                    }
                    $update['image'] = $request -> image_name;
                }

                $sponsor -> update($update);
            }
            catch(QueryException $e){
                \Log::error('Error updating show: '.$e);

                if(file_exists(public_path() . '/images/sponsors/' . $request -> image_name)){
                    Storage::delete(public_path() . '/images/sponsors/' . $request -> image_name);
                }
                
                flash('Ops! An error has ocurred. Please try again.') -> error();
                return redirect() -> route('sponsors.index')
                -> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events);
            }
        
            flash('The sponsor has been updated correctly.') -> success();
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
        
        $sponsor = Sponsor::find($id);
        if(file_exists(public_path() . '/images/sponsors/' . $sponsor -> image)){
            Storage::delete(public_path() . '/images/sponsors/' . $sponsor -> image);
        }
        $sponsor -> delete();

        flash('The sponsor has been deleted correctly.') -> success();
        return redirect() -> route('dashboard')
        -> with('user', $user -> name)
        -> with('sales', $this -> sales)
        -> with('events', $this -> events);
    }
}
