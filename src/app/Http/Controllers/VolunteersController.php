<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use App\Volunteer;
use App\Show;
use App\Event;
use Config;

class VolunteersController extends Controller
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
        $volunteers = Volunteer::orderBy(Config::get('constants.fields.IdField'),'ASC')->paginate(5);
        
        if(empty($volunteers)){
            $response = \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.NonExistingVolunteerCode'), 
                'msg' => Config::get('constants.msgs.NonExistingVolunteerMsg')]], 500);

            return view('') -> with('response', $response);
        }
        
        $response = \Response::json(['response' => $volunteers,'error' => 
            ['code' => Config::get('constants.codes.OkCode'), 
            'msg' => Config::get('constants.msgs.OkMsg')]], 200);

        return view('') 
        -> with('response', $response)
        -> with('volunteers', $volunteers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sponsors = Sponsor::orderBy('name','DESC')-> lists('name','id');
        $shows = Show::orderBy('name','DESC')-> lists('name','id');

        return view('')
        -> with('sponsors', $sponsors)
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
        if (!$request -> name) {
            $response = \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.MissingInputCode'), 
                'msg'   => Config::get('constants.msgs.MissingInputMsg')]], 500);

            return view('') -> with('response', $response);
        }
        
        $rules = [
            'name'     => 'required|min:2|max:80|unique:volunteers',
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

            $volunteer = new Volunteer($request->all());

            $data = DB::table(Config::get('constants.tables.VolunteersTable'))
                ->where(Config::get('constants.fields.NameField'), $volunteer -> name)->first();

            if(!empty($data)){
                return \Response::json(['response' => '', 'error' => 
                    [ 'code' => Config::get('constants.codes.ExistingVolunteerCode'), 
                    'msg' => Config::get('constants.msgs.ExistingVolunteerMsg')]], 500);
            }

            if($request->file('image')){
                $file = $request -> file('image');
                $name = $request -> name . '.' . $file->getClientOriginalExtension();
                $path = public_path() . '/images/volunteers/';
                $file -> move($path,$name);
                $volunteer -> image = $name;
            }

            $volunteer -> save();

            if(!empty($request -> show_id)){
                $show = Show::find($request -> show_id);
                if(empty($show)){
                    $response = \Response::json(['response' => '', 'error' => 
                    ['code' => Config::get('constants.codes.NonExistingShowsCode'), 
                    'msg'   => Config::get('constants.msgs.NonExistingShowsMsg')]], 500);

                    return view('') -> with('response', $response);
                }
                $volunteer -> shows() -> attach($request -> show_id);
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
        $volunteer = Volunteer::find($id);
    
        if(empty($volunteer)){
            $response = \Response::json(['response' => $volunteer,'error' => 
                ['code' => Config::get('constants.codes.NonExistingVolunteerCode'), 
                'msg' => Config::get('constants.msgs.NonExistingVolunteerMsg')]], 500);
        }

        $my_shows  = $volunteer -> shows -> pluck('id', 'name')->all();
        $my_events = $volunteer -> events -> pluck('id', 'name')->all();
        $volunteer -> sponsor;

        $response = \Response::json(['response' => $volunteer,'error' => 
            ['code' => Config::get('constants.codes.OkCode'), 
            'msg' => Config::get('constants.msgs.OkMsg')]], 200);
        
        return view('')    
        -> with('response', $response)
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
            $response = \Response::json(['response' => '','error' => 
            ['code' => Config::get('constants.codes.NonExistingEventCode'), 
            'msg'   => Config::get('constants.msgs.NonExistingEventMsg')]], 500);

            return view('') -> with('response', $response);
        }

        $my_shows  = $volunteer -> shows -> pluck('id')->all();
        $my_events = $volunteer -> events -> pluck('id')->all();
        $volunteer -> sponsor;

        $events = Event::orderBy('name','DESC') -> pluck('name','id');
        $shows  = Show::orderBy('name','DESC') -> pluck('name','id');
        
        return view('')
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
        if(!$request -> name && !$request -> status && !$request -> description && !$request->file('image')){
            $response = \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.MissingInputCode'), 
                'msg'   => Config::get('constants.msgs.MissingInputMsg')]], 500);

            return view('') -> with('response', $response);
        }

        else{
            $volunteer = Volunteer::find($id);

            $update = array();
            try{
                if(!empty($request -> show_id)){
                    $show = Show::find($request -> show_id);
                    if(empty($show)){
                        $response = \Response::json(['response' => '', 'error' => 
                        ['code' => Config::get('constants.codes.NonExistingShowsCode'), 
                        'msg'   => Config::get('constants.msgs.NonExistingShowsMsg')]], 500);

                        return view('') -> with('response', $response);
                    }
                    
                    $volunteer -> shows() -> attach($request -> show_id);
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

                if(!empty($request -> file('image'))){
                    $file = $request -> file('image');
                    $name = $request -> name . '.' . $file->getClientOriginalExtension();
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
        $volunteer = Volunteer::find($id);
        $volunteer -> delete();

        $response = \Response::json(['response' => '','error' => 
            ['code' => Config::get('constants.codes.OkCode'), 
            'msg' => Config::get('constants.msgs.OkMsg')]], 200);

        return view('') -> with('response', $response);
    }
}
