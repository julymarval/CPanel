<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use Config;
use App\Event;
use App\Sale;
use App\Sponsor;
use App\Volunteer;

class EventsController extends Controller
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
        $this->middleware('jwt.auth',['except' => ['index', 'show']]);
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
        $events = Event::orderBy(Config::get('constants.fields.IdField'),'DESC')->paginate(5);
        
        if(empty($events)){
                    
            $code = Config::get('constants.codes.NonExistingEventCode'); 
            $msg = Config::get('constants.msgs.NonExistingEventMsg');

            return view('events.event') 
            -> with('events', $events)
            -> with('code', $code)
            -> with('msg',$msg);
        }
 
        $code = Config::get('constants.codes.OkCode');
        $msg = Config::get('constants.msgs.OkMsg');

        return view('events.event')
        -> with('code', $code)
        -> with('msg', $msg)
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

        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');

        return view('events.create_event')
        -> with('code', $code)
        -> with('msg', $msg)
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
        $user = JWTAuth::toUser($request -> input('Authorization'));

       if (!$request -> name || !$request -> date) {
            
                $code = Config::get('constants.codes.MissingInputCode'); 
                $msg   = Config::get('constants.msgs.MissingInputMsg');
            
            return view('admin_dashboard')
            -> with('user', $user -> name)
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
        }
        
        $rules = [
            'name' => 'required|min:2|max:80',
            'date' => 'required|date_format:Y-m-d|after: ' . date('Y-m-d'),
        ];

        try {
            
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                
                $code = Config::get('constants.codes.InvalidInputCode'); 
                $msg = Config::get('constants.msgs.InvalidInputMsg') . ': ' . $validator->errors();

                return view('admin_dashboard')
                -> with('user', $user -> name)
                -> with('sales', $this -> sales)
                -> with('events', $this -> events)
                -> with('code', $code)
                -> with('msg', $msg);
            }

            $event = new Event($request->all());

            $data = DB::table(Config::get('constants.tables.EventsTable'))
                ->where(Config::get('constants.fields.NameField'), $event -> name)->first();

            if(!empty($data)){
                
                $code = Config::get('constants.codes.ExistingEventCode');
                $msg = Config::get('constants.msgs.ExistingEventMsg');

                return view('admin_dashboard')
                -> with('user', $user -> name)
                -> with('sales', $this -> sales)
                -> with('events', $this -> events)
                -> with('code', $code)
                -> with('msg', $msg);
            }

            if($request->file('image')){
                $file = $request -> file('image');
                $name = $request -> name . '.' . $file->getClientOriginalExtension();
                $path = public_path() . '/images/events/';
                $file -> move($path,$name);
                $event -> image = $name;
            }

            $event -> save();

            if($request -> volunteer_id){      
                foreach($request -> volunteer_id as $id){
                    $volunteer = Volunteer::find($id);
                    if(empty($volunteer)){
                        $code = Config::get('constants.codes.NonExistingVolunteerCode');
                        $msg   = Config::get('constants.msgs.NonExistingVolunteerMsg');
                        
                        return view('admin_dashboard')
                        -> with('user', $user -> name)
                        -> with('sales', $this -> sales)
                        -> with('events', $this -> events)
                        -> with('code', $code)
                        -> with('msg', $msg);
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
                        
                        $code = Config::get('constants.codes.NonExistingVolunteerCode');
                        $msg   = Config::get('constants.msgs.NonExistingVolunteerMsg');

                        return view('admin_dashboard')
                        -> with('user', $user -> name)
                        -> with('sales', $this -> sales)
                        -> with('events', $this -> events)
                        -> with('code', $code)
                        -> with('msg', $msg);
                    }
                }
                foreach($request -> sponsor_id as $id){
                    $event -> sponsors() -> attach($id);
                }
            }

            $code = Config::get('constants.codes.OkCode');
            $msg = Config::get('constants.msgs.OkMsg');
            
            return view('admin_dashboard')
            -> with('user', $user -> name)
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);

        } catch (Exception $e) {
            \Log::info('Error creating sale: '.$e);
            
            $code = Config::get('constants.codes.InternalErrorCode'); 
            $msg = Config::get('constants.msgs.InternalErrorMsg');
            
            return view('admin_dashboard')
            -> with('user', $user -> name)
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
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
        $event = Event::find($id);

        if(empty($event)){
            
            $code = Config::get('constants.codes.NonExistingEventCode'); 
            $msg = Config::get('constants.msgs.NonExistingEventMsg');
                
            return view('events.show_event')
            -> with('code', $code)
            > with('event', $event)
            -> with('msg', $msg);
        }

        $my_sponsors   = $event -> sponsors   -> pluck('name')->all();
        $my_volunteers = $event -> volunteers -> pluck('name')->all();

        $code = Config::get('constants.codes.OkCode');
        $msg = Config::get('constants.msgs.OkMsg');
        
        return view('events.show_event')
        -> with('code', $code)
        -> with('msg', $msg)
        -> with('event', $event)
        -> with('my_sponsors', $my_sponsors)
        -> with('my_volunteers', $my_volunteers);

        
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
            
            $code = Config::get('constants.codes.NonExistingEventCode');
            $msg = Config::get('constants.msgs.NonExistingEventMsg');

            return view('events.edit_event') 
            -> with('code', $code)
            > with('event', $event)
            -> with('msg', $msg);
        }

        $my_volunteers = $event -> volunteers -> pluck('id', 'name')->all();        
        $my_sponsors   = $event -> sponsors   -> pluck('id', 'name')->all();
        $volunteers = Volunteer::orderBy('name','DESC') -> pluck('name','id')->all();
        $sponsors = Sponsor::orderBy('name','DESC') -> pluck('name','id')->all();

        $code = Config::get('constants.codes.OkCode');
        $msg = Config::get('constants.msgs.OkMsg');

        return view('events.edit_event')
        -> with('code', $code)
        -> with('msg', $msg)
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
        $user = JWTAuth::toUser($request -> input('Authorization'));

        if ((!$request -> name) && (!$request -> date) && (!$request -> description) 
            && !$request -> volunteer_id && !$request -> sponsor_id) {
            
            $code = Config::get('constants.codes.MissingInputCode'); 
            $msg = Config::get('constants.msgs.MissingInputMsg');

            return view('admin_dashboard') 
            -> with('user', $user -> name)
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
        }

        else{
            $event = Event::find($id);

            $update = array();
            try{
                if(!empty($request -> volunteer_id)){
                    foreach($request -> volunteer_id as $id){
                        $volunteer = Volunteer::find($id);
                        
                        if(empty($volunteer)){
                            
                            $code = Config::get('constants.codes.NonExistingVolunteerCode'); 
                            $msg = Config::get('constants.msgs.NonExistingVolunteerMsg');

                                return view('admin_dashboard')
                                -> with('user', $user -> name)
                                -> with('sales', $this -> sales)
                                -> with('events', $this -> events)
                                -> with('code', $code)
                                -> with('msg', $msg);
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
                            
                            $code = Config::get('constants.codes.NonExistingVolunteerCode');
                            $msg = Config::get('constants.msgs.NonExistingVolunteerMsg');
                            
                            return view('admin_dashboard') 
                            -> with('user', $user -> name)
                            -> with('sales', $this -> sales)
                            -> with('events', $this -> events)
                            -> with('code', $code)
                            -> with('msg', $msg);
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
                        
                        $code = Config::get('constants.codes.InvalidInputCode');
                        $msg = Config::get('constants.msgs.InvalidInputMsg') . ': ' . $validator->errors();
                        
                        return view('admin_dashboard') 
                        -> with('user', $user -> name)
                        -> with('sales', $this -> sales)
                        -> with('events', $this -> events)
                        -> with('code', $code)
                        -> with('msg', $msg);
                    }
    
                }
                
                if(!empty($request -> date)){
                    $update['date'] =  $request -> date;

                    $rules = [
                        'date' => 'date_format:Y-m-d|after: ' . date('Y-m-d'),
                    ];
                        
                    $validator = \Validator::make($request->all(), $rules);
                    if ($validator->fails()) {
                        
                        $code = Config::get('constants.codes.InvalidInputCode'); 
                        $msg = Config::get('constants.msgs.InvalidInputMsg') . ': ' . $validator->errors();

                        return view('admin_dashboard') 
                        -> with('user', $user -> name)
                        -> with('sales', $this -> sales)
                        -> with('events', $this -> events)
                        -> with('code', $code)
                        -> with('msg', $msg);
                    }
    
                }
                
                if(!empty($request -> description)){
                    $update['description'] =  $request -> description;
                }

                if(!empty($request -> file('image'))){
                    $file = $request -> file('image');
                    $name = $event -> name . '.' . $file->getClientOriginalExtension();
                    if(file_exists(public_path() . '/images/events/' . $event -> image)){
                        Storage::delete(public_path() . '/images/events/' . $event -> image);
                    }
                    $path = public_path() . '/images/events/';
                    $file -> move($path,$name);
                    $update['image'] = $name;
                }

                $event -> update($update);
            }
            catch(QueryException $e){
                
                \Log::error('Error creating sale: '.$e);
                $code = Config::get('constants.codes.InternalErrorCode'); 
                $msg =  Config::get('constants.msgs.InternalErrorMsg');
                
                return view('admin_dashboard') 
                -> with('user', $user -> name)
                -> with('sales', $this -> sales)
                -> with('events', $this -> events)
                -> with('code', $code)
                -> with('msg', $msg);
            }
        
            $code = Config::get('constants.codes.OkCode');
            $msg = Config::get('constants.msgs.OkMsg');

            return view('admin_dashboard') 
            -> with('user', $user -> name)
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
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
        $user = JWTAuth::toUser($request -> input('Authorization'));
        
        $event = Event::find($id);
        $event -> delete();

        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');

        return view('admin_dashboard')
        -> with('user', $user -> name)
        -> with('sales', $this -> sales)
        -> with('events', $this -> events)
        -> with('code', $code)
        -> with('msg', $msg);
    }
}
