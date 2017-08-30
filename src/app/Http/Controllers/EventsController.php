<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use Config;
use App\Event;
use App\Sponsor;
use App\Volunteer;

class EventsController extends Controller
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
        $events = Event::orderBy(Config::get('constants.fields.IdField'),'ASC')->paginate(5);
        
        if(empty($events)){
            $response = \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.NonExistingEventCode'), 
                'msg' => Config::get('constants.msgs.NonExistingEventMsg')]], 500);

            return view('') -> with('response', $response);
        }
        
        $response = \Response::json(['response' => $events,'error' => 
            ['code' => Config::get('constants.codes.OkCode'), 
            'msg' => Config::get('constants.msgs.OkMsg')]], 200);

        return view('')
        -> with('events', $events)
        -> with('response', $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sponsors   = Sponsor::orderBy('name','DESC')-> lists('name','id');
        $volunteers = Volunteer::orderBy('name','DESC')-> lists('name','id');

        return view('')
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
        if (!$request -> name) {
            $response = \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.MissingInputCode'), 
                'msg'   => Config::get('constants.msgs.MissingInputMsg')]], 500);
            
            return view('') -> with('response', $response);
        }
        
        $rules = [
            'name' => 'required|min:2|max:80',
            'date' => 'date_format:Y-m-d|after: ' . date('Y-m-d'),
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

            $event = new Event($request->all());

            $data = DB::table(Config::get('constants.tables.EventsTable'))
                ->where(Config::get('constants.fields.NameField'), $event -> name)->first();

            if(!empty($data)){
                $response = \Response::json(['response' => '', 'error' => 
                    [ 'code' => Config::get('constants.codes.ExistingEventCode'), 
                    'msg' => Config::get('constants.msgs.ExistingEventMsg')]], 500);

                return view('') -> with('response', $response);
            }

            if($request->file('image')){
                $file = $request -> file('image');
                $name = $request -> name . '.' . $file->getClientOriginalExtension();
                $path = public_path() . '/images/events/';
                $file -> move($path,$name);
                $event -> image = $name;
            }

            $event -> save();

            if(!empty($request -> volunteer_id)){
                $volunteer = Volunteer::find($request -> volunteer_id);
                
                if(empty($volunteer)){
                    $response = \Response::json(['response' => '', 'error' => 
                        ['code' => Config::get('constants.codes.NonExistingVolunteerCode'), 
                        'msg'   => Config::get('constants.msgs.NonExistingVolunteerMsg')]], 500);
                    
                    return view('') -> with('response', $response);
                }
                $event -> volunteers() -> attach($request -> volunteer_id);
            }

            if(!empty($request -> sponsor_id)){
                $sponsor = Sponsor::find($request -> sponsor_id);
                
                if(empty($sponsor)){
                    $response = \Response::json(['response' => '', 'error' => 
                        ['code' => Config::get('constants.codes.NonExistingVolunteerCode'), 
                        'msg'   => Config::get('constants.msgs.NonExistingVolunteerMsg')]], 500);

                    return view('') -> with('response', $response);
                }
                $event -> sponsors() -> attach($request -> sponsor_id);
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
        $event = Event::find($id);

        if(empty($event)){
            $response = \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.NonExistingEventCode'), 
                'msg' => Config::get('constants.msgs.NonExistingEventMsg')]], 500);
                
            return view('')-> with('response', $response);
        }

        $my_sponsors   = $event -> sponsors   -> pluck('id', 'name')->all();
        $my_volunteers = $event -> volunteers -> pluck('id', 'name')->all();

        $sponsors   = Sponsor::orderBy('name','DESC') -> pluck('name','id');
        $volunteers = Volunteer::orderBy('name','DESC') -> pluck('name','id');

        $response = \Response::json(['response' => $event,'error' => 
            ['code' => Config::get('constants.codes.OkCode'), 
            'msg' => Config::get('constants.msgs.OkMsg')]], 200);
        
        return view('')
        -> with('response', $response)
        -> with('event', $event)
        -> with('my_sponsors', $my_sponsors)
        -> with('my_volunteers', $my_volunteers)
        -> with('sponsors',$sponsors)
        -> with('volunteers',$volunteers);

        
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
            $response = \Response::json(['response' => '','error' => 
            ['code' => Config::get('constants.codes.NonExistingEventCode'), 
            'msg' => Config::get('constants.msgs.NonExistingEventMsg')]], 500);

            return view('') -> with('response', $response);
        }

        $my_volunteers = $event -> volunteers -> pluck('id', 'name')->all();        
        $my_sponsors   = $event -> sponsors   -> pluck('id', 'name')->all();

        $response = \Response::json(['response' => $event,'error' => 
        ['code' => Config::get('constants.codes.OkCode'), 
        'msg' => Config::get('constants.msgs.OkMsg')]], 200);

        return view('')
        -> with('response', $response)
        -> with('event', $event)
        -> with('volunteers', $my_volunteers)
        -> with('sponsors', $my_sponsors);

        
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
        if ((!$request -> name) && (!$request -> date) && (!$request -> description) 
            && !$request -> volunteer_id && !$request -> sponsor_id) {
            $response = \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.MissingInputCode'), 
                'msg'   => Config::get('constants.msgs.MissingInputMsg')]], 401);

            return view('') -> with('response', $response);
        }

        else{
            $event = Event::find($id);

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
                    $event -> volunteers() -> attach($request -> volunteer_id);
                }

                if(!empty($request -> sponsor_id)){
                    $sponsor = Sponsor::find($request -> sponsor_id);
                    
                    if(empty($sponsor)){
                        $response = \Response::json(['response' => '', 'error' => 
                            ['code' => Config::get('constants.codes.NonExistingVolunteerCode'), 
                            'msg'   => Config::get('constants.msgs.NonExistingVolunteerMsg')]], 500);
                        
                        return view('') -> with('response', $response);
                    }
                    $event -> sponsors() -> attach($request -> sponsor_id);
                }

                if(!empty($request -> name)){
                    $update['name'] = $request -> name;
                    $rules = [
                        'name' => 'min:2|max:80|unique:events',
                    ];
                               
                    $validator = \Validator::make($request -> name, $rules);
                    if ($validator->fails()) {
                        $response = \Response::json(['response' => '','error' => 
                            ['code' => Config::get('constants.codes.InvalidInputCode'), 
                            'msg' => Config::get('constants.msgs.InvalidInputMsg') . ': ' .
                            $validator->errors()]], 500);
                        
                        return view('') -> with('response', $response);
                    }
    
                }
                
                if(!empty($request -> date)){
                    $update['date'] =  $request -> date;

                    $rules = [
                        'date' => 'date_format:Y-m-d|after: ' . date('Y-m-d'),
                    ];
            
                        
                    $validator = \Validator::make($request->all(), $rules);
                    if ($validator->fails()) {
                        $response = \Response::json(['response' => '','error' => 
                            ['code' => Config::get('constants.codes.InvalidInputCode'), 
                            'msg' => Config::get('constants.msgs.InvalidInputMsg') . ': ' .
                            $validator->errors()]], 500);

                        return view('') -> with('response', $response);
                    }
    
                }
                
                if(!empty($request -> description)){
                    $update['description'] =  $request -> description;
                }

                if(!empty($request -> file('image'))){
                    $file = $request -> file('image');
                    $name = $request -> name . '.' . $file->getClientOriginalExtension();
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
        $event = Event::find($id);
        $event -> delete();

        $response = \Response::json(['response' => '','error' => 
            ['code' => Config::get('constants.codes.OkCode'), 
            'msg' => Config::get('constants.msgs.OkMsg')]], 200);

        return view('') -> with('response', $response);
    }
}
