<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use Config;
use App\Sponsor;
use App\Event;
use App\Volunteer;

class SponsorsController extends Controller
{
    
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
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sponsors = Sponsor::orderBy(Config::get('constants.fields.IdField'),'ASC') -> paginate(5);

        if(empty($sponsors)){
            $response = \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.NonExistingSponsorsCode'), 
                'msg' => Config::get('constants.msgs.NonExistingSponsorsMsg')]], 500);

            return view('') -> with('response', $response);
        }
        
        $response = \Response::json(['response' => $sponsors,'error' => 
            ['code' => Config::get('constants.codes.OkCode'), 
            'msg' => Config::get('constants.msgs.OkMsg')]], 200);

        return view('') 
        -> with('response', $response)
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

        return view('')
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
        if (!$request -> name && !$request -> status && !$request -> level) {
            $response = \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.MissingInputCode'), 
                'msg'   => Config::get('constants.msgs.MissingInputMsg')]], 500);

            return view('') -> with('response', $response);
        }
        
        $rules = [
            'name'   => 'required|min:2|max:80',
            'status' => 'required',
            'level'  => 'required'
        ];

        try {
            
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $response = \Response::json(['response' => '','error' => 
                    ['code' => Config::get('constants.codes.InvalidInputCode'), 
                    'msg' => Config::get('constants.msgs.InvalidInputMsg') . ': ' .
                    $validator->errors()]], 500);

                return view('') -> with('response', $response);
            }

            $sponsor = new Sponsor($request->all());

            $data = DB::table(Config::get('constants.tables.SponsorsTable'))
                ->where(Config::get('constants.fields.NameField'), $sponsor -> name)->first();

            if(!empty($data)){
                $response = \Response::json(['response' => '', 'error' => 
                    [ 'code' => Config::get('constants.codes.ExistingSponsorCode'), 
                    'msg' => Config::get('constants.msgs.ExistingSponsorMsg')]], 500);

                return view('') -> with('response', $response);
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
                    $response = \Response::json(['response' => '', 'error' => 
                        ['code' => Config::get('constants.codes.NonExistingVolunteerCode'), 
                        'msg'   => Config::get('constants.msgs.NonExistingVolunteerMsg')]], 500);

                    return view('') -> with('response', $response);
                }
                $sponsor -> events() -> attach($request -> event_id);
            }

            $response = \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.OkCode'), 
                'msg' => Config::get('constants.msgs.OkMsg')]], 200);

            return view('') -> with('response', $response);
            
        } catch (Exception $e) {
            \Log::info('Error creating sale: '.$e);
            $response = \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.InternalErrorCode'), 
                'msg' => Config::get('constants.msgs.InternalErrorMsg')]], 500);

            return view('') -> with('response', $response);
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
            $response = \Response::json(['response' => '','error' => 
            ['code' => Config::get('constants.codes.NonExistingSponsorsCode'), 
            'msg'   => Config::get('constants.msgs.NonExistingSponsorsMsg')]], 500);

            return view('') -> with('response', $response);
        }

        $my_events = $sponsor -> events -> pluck('id', 'name') -> all();
        $my_volunteers = $sponsor -> volunteers -> pluck('id', 'name') -> all();
        
        $response = \Response::json(['response' => $sponsor,'error' => 
        ['code' => Config::get('constants.codes.OkCode'), 
        'msg' => Config::get('constants.msgs.OkMsg')]], 200);

        return view('')
        -> with('response',$response)
        -> with('sponsor', $sponsor)
        -> with('my_events', $my_events)
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
        $sponsor = Sponsor::find($id);
        
        if(empty($sponsor)){
            $response = \Response::json(['response' => '','error' => 
            ['code' => Config::get('constants.codes.NonExistingSponsorsCode'), 
            'msg' => Config::get('constants.msgs.NonExistingSponsorsMsg')]], 500);

            return view('') -> with('response', $response);
        }

        $my_volunteers = $sponsor -> volunteers -> pluck('id', 'name') -> all();
        $my_events     = $sponsor -> events -> pluck('id','name') -> all();

        $volunteers = Volunteer::orderBy('name','DESC') -> pluck('name','id');
        $events     = Event::orderBy('name','DESC') -> pluck('name', 'id');
        
        return view('')
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
        if(!$request -> name && !$request -> status && !$request -> description && !$request -> event_id 
            && !$request->file('image') && !$request -> volunteer_id && !$request -> level ){
                $response = \Response::json(['response' => '','error' => 
                    ['code' => Config::get('constants.codes.MissingInputCode'), 
                    'msg'   => Config::get('constants.msgs.MissingInputMsg')]], 500);

                return view('') -> with('response', $response);
        }
            
        else{
            $sponsor = Sponsor::find($id);

            $update = array();
            try{
                if(!empty($request -> volunteer_id)){
                    $volunteer = Volunteer::find($request -> volunteer_id);
                    
                    if(empty($volunteer)){
                        $response = \Response::json(['response' => '', 'error' => 
                            ['code' => Config::get('constants.codes.NonExistingVolunteerCode'), 
                            'msg'   => Config::get('constants.msgs.NonExistingVolunteerMsg')]], 500);

                        return view('') -> with('response', $response);
                    }
                    $update['volunteer_id'] = $request -> volunteer_id;
                }

                if(!empty($request -> event_id)){
                    $event = Event::find($request -> event_id);
                    
                    if(empty($event)){
                        $response = \Response::json(['response' => '', 'error' => 
                            ['code' => Config::get('constants.codes.NonExistingEventCode'), 
                            'msg'   => Config::get('constants.msgs.NonExistingEventMsg')]], 500);
                            
                        return view('') -> with('response', $response);
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
                    $name = $request -> name . '.' . $file->getClientOriginalExtension();
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
                $response = \Response::json(['response' => '','error' => 
                    ['code' => Config::get('constants.codes.InternalErrorCode'), 
                    'msg' => Config::get('constants.msgs.InternalErrorMsg')]], 500);

                return view('') -> with('response', $response);
            }
        
            $response = \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.OkCode'), 
                'msg' => Config::get('constants.msgs.OkMsg')]], 200);

            return view('') -> with('response', $response);
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
        $sponsor = Sponsor::find($id);
        $sponsor -> delete();

        $response = \Response::json(['response' => '','error' => 
            ['code' => Config::get('constants.codes.OkCode'), 
            'msg' => Config::get('constants.msgs.OkMsg')]], 200);

        return view('') -> with('response', $response);
    }
}
