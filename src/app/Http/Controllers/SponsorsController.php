<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
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
            $code = Config::get('constants.codes.NonExistingSponsorsCode'); 
            $msg = Config::get('constants.msgs.NonExistingSponsorsMsg');

            return view('sponsors.sponsor') 
            -> with('code', $code)
            -> with('msg', $msg);
        }
        
        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');

        return view('sponsors.sponsor') 
        -> with('code', $code)
        -> with('msg', $msg)
        -> with('sponsors', $sponsors);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $events   = Event::orderBy('name','DESC')-> lists('name','id');
        $volunteers = Volunteer::orderBy('name','DESC')-> lists('name','id');

        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');

        return view('sponsors.create_sponsor') 
        -> with('code', $code)
        -> with('msg', $msg)
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
        //$user = JWTAuth::toUser($request -> input('Authorization'));

        if (!$request -> name && !$request -> status && !$request -> level) {
            $code = Config::get('constants.codes.MissingInputCode');
            $msg  = Config::get('constants.msgs.MissingInputMsg');

            return view('admin_dashboard') 
            //-> with('user', $user -> name)
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
        }
        
        $rules = [
            'name'   => 'required|min:2|max:80',
            'status' => 'required',
            'level'  => 'required'
        ];

        try {
            
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = Config::get('constants.codes.InvalidInputCode'); 
                $msg = Config::get('constants.msgs.InvalidInputMsg') . ': ' . $validator->errors();

                return view('admin_dashboard')
                //-> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events)
                -> with('code', $code)
                -> with('msg', $msg);
            }

            $sponsor = new Sponsor($request->all());

            $data = DB::table(Config::get('constants.tables.SponsorsTable'))
                ->where(Config::get('constants.fields.NameField'), $sponsor -> name)->first();

            if(!empty($data)){
                $code = Config::get('constants.codes.ExistingSponsorCode');
                $msg = Config::get('constants.msgs.ExistingSponsorMsg');

                return view('admin_dashboard') 
                //-> with('user', $user -> name)
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
                $sponsor -> image = $name;
            }

            $sponsor -> save();

            if(!empty($request -> event_id)){
                $event = Event::find($request -> event_id);
                
                if(empty($sponsor)){
                    $code = Config::get('constants.codes.NonExistingVolunteerCode'); 
                    $msg = Config::get('constants.msgs.NonExistingVolunteerMsg');

                    return view('admin_dashboard') 
                    //-> with('user', $user -> name)
                    -> with('sales', $this -> sales)
                    -> with('events', $this -> events)
                    -> with('code', $code)
                    -> with('msg', $msg);
                }
                $sponsor -> events() -> attach($request -> event_id);
            }

            $code = Config::get('constants.codes.OkCode'); 
            $msg = Config::get('constants.msgs.OkMsg');

            return view('admin_dashboard') 
            //-> with('user', $user -> name)
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
            
        } catch (Exception $e) {
            \Log::info('Error creating sale: '.$e);
            $code = Config::get('constants.codes.InternalErrorCode'); 
            $msg = Config::get('constants.msgs.InternalErrorMsg');

            return view('admin_dashboard') 
            //-> with('user', $user -> name)
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
        
        $sponsor = Sponsor::find($id);
        
        if(empty($sponsor)){
            $code = Config::get('constants.codes.NonExistingSponsorsCode'); 
            $msg = Config::get('constants.msgs.NonExistingSponsorsMsg');

            return view('sponsors.show_sponsor') 
            -> with('code', $code)
            -> with('msg', $msg);
        }

        $my_events = $sponsor -> events -> pluck('name') -> all();
        $my_volunteer = Volunteer::find($sponsor -> volunteer_id);
        
        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');

        return view('sponsors.show_sponsor') 
        -> with('code', $code)
        -> with('msg', $msg)
        -> with('sponsor', $sponsor)
        -> with('my_events', $my_events)
        -> with('volunteer', $my_volunteer -> name);
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
            $code = Config::get('constants.codes.NonExistingSponsorsCode'); 
            $msg = Config::get('constants.msgs.NonExistingSponsorsMsg');

            return view('sponsors.edit_sponsor') 
            -> with('code', $code)
            -> with('msg', $msg);
        }

        $my_volunteers = $sponsor -> volunteers -> pluck('id', 'name') -> all();
        $my_events     = $sponsor -> events -> pluck('id','name') -> all();

        $volunteers = Volunteer::orderBy('name','DESC') -> pluck('name','id');
        $events     = Event::orderBy('name','DESC') -> pluck('name', 'id');

        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');
        
        return view('sponsors.edit_sponsor')
        -> with('code', $code)
        -> with('msg', $msg)
        -> with('sponsor', $sponsor)
        -> with('my_volunteers', $my_volunteers)
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
        //$user = JWTAuth::toUser($request -> input('Authorization'));

        if(!$request -> name && !$request -> status && !$request -> description && !$request -> event_id 
            && !$request->file('image') && !$request -> volunteer_id && !$request -> level ){
                $code = Config::get('constants.codes.MissingInputCode'); 
                $msg = Config::get('constants.msgs.MissingInputMsg');

                return view('admin_dashboard')
                //-> with('user', $user -> name)
                -> with('sales', $this -> sales)
                -> with('events', $this -> events)
                -> with('code', $code)
                -> with('msg', $msg);
        }
            
        else{
            $sponsor = Sponsor::find($id);

            $update = array();
            try{
                if(!empty($request -> volunteer_id)){
                    $volunteer = Volunteer::find($request -> volunteer_id);
                    
                    if(empty($volunteer)){
                        $code = Config::get('constants.codes.NonExistingVolunteerCode');
                        $msg = Config::get('constants.msgs.NonExistingVolunteerMsg');

                            return view('admin_dashboard')
                            //-> with('user', $user -> name)
                            -> with('sales', $this -> sales)
                            -> with('events', $this -> events)
                            -> with('code', $code)
                            -> with('msg', $msg);
                    }
                    $update['volunteer_id'] = $request -> volunteer_id;
                }

                if(!empty($request -> event_id)){
                    $event = Event::find($request -> event_id);
                    
                    if(empty($event)){
                        $code = Config::get('constants.codes.NonExistingEventCode'); 
                        $msg = Config::get('constants.msgs.NonExistingEventMsg');
                            
                        return view('admin_dashboard')
                        //-> with('user', $user -> name)
                        -> with('sales', $this -> sales)
                        -> with('events', $this -> events)
                        -> with('code', $code)
                        -> with('msg', $msg);
                    }
                    $sponsor -> events() -> attach($request -> event_id);
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

                if(!empty($request->file('image'))){
                    $file = $request -> file('image');
                    $name = $sponsor -> name . '.' . $file->getClientOriginalExtension();
                    if(file_exists(public_path() . '/images/sponsors/' . $sponsor -> image)){
                        Storage::delete(public_path() . '/images/sponsors/' . $sponsor -> image);
                    }
                    $path = public_path() . '/images/shows/';
                    $file -> move($path,$name);
                    $update['image'] = $name;
                }

                $sponsor -> update($update);
            }
            catch(QueryException $e){
                \Log::error('Error updating show: '.$e);
                $code = Config::get('constants.codes.InternalErrorCode'); 
                $msg = Config::get('constants.msgs.InternalErrorMsg');

                return view('admin_dashboard')
                //-> with('user', $user -> name)
                -> with('sales', $this -> sales)
                -> with('events', $this -> events)
                -> with('code', $code)
                -> with('msg', $msg);
            }
        
            $code = Config::get('constants.codes.OkCode'); 
            $msg = Config::get('constants.msgs.OkMsg');

            return view('admin_dashboard')
            //-> with('user', $user -> name)
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
        //$user = JWTAuth::toUser($request -> input('Authorization'));

        $sponsor = Sponsor::find($id);
        $sponsor -> delete();

        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');

        return view('admin_dashboard')
        //-> with('user', $user -> name)
        -> with('sales', $this -> sales)
        -> with('events', $this -> events)
        -> with('code', $code)
        -> with('msg', $msg);
    }
}
