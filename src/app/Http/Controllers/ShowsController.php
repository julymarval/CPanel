<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use App\Show;
use App\Volunteer;
use Config;

class ShowsController extends Controller
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
        $shows = Show::orderBy(Config::get('constants.fields.IdField'),'ASC')->paginate(5);
        
        if(empty($shows)){
            return \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.NonExistingShowsCode'), 
                'msg' => Config::get('constants.msgs.NonExistingShowsMsg')]], 500);
        }

        return \Response::json(['response' => $shows,'error' => 
            ['code' => Config::get('constants.codes.OkCode'), 
            'msg' => Config::get('constants.msgs.OkMsg')]], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if (empty($request -> name)) {
            return \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.MissingInputCode'), 
                'msg'   => Config::get('constants.msgs.MissingInputMsg')]], 500);
        }
        
        $rules = [
            'name'     => 'required|min:2|max:80|unique:shows',
            'schedule' => 'required'
        ];

        try {
            
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return \Response::json(['response' => '','error' => 
                    ['code' => Config::get('constants.codes.InvalidInputCode'), 
                    'msg' => Config::get('constants.msgs.InvalidInputMsg') . ': ' .
                    $validator->errors()]], 500);
            }

            $show = new Show($request->all());

            $data = DB::table(Config::get('constants.tables.ShowsTable'))
                ->where(Config::get('constants.fields.NameField'), $show->name)->first();

            if(!empty($data)){
                return \Response::json(['response' => '', 'error' => 
                    ['code' => Config::get('constants.codes.ExistingShowCode'), 
                    'msg'   => Config::get('constants.msgs.ExistingShowMsg')]], 500);
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
                
                $volunteer = Volunteer::find($request -> volunteer_id);
                
                if(empty($volunteer)){
                    return \Response::json(['response' => '', 'error' => 
                    ['code' => Config::get('constants.codes.NonExistingVolunteerCode'), 
                    'msg'   => Config::get('constants.msgs.NonExistingVolunteerMsg')]], 500);
                }

                $show -> volunteers() -> attach($request -> volunteer_id);
            }

            return \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.OkCode'), 
                'msg' => Config::get('constants.msgs.OkMsg')]], 200);
            
        } catch (Exception $e) {
            \Log::info('Error creating sale: '.$e);
            return \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.InternalErrorCode'), 
                'msg' => Config::get('constants.msgs.InternalErrorMsg')]], 500);
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
            return \Response::json(['response' => $show,'error' => 
            ['code' => Config::get('constants.codes.NonExistingSalesCode'), 
            'msg' => Config::get('constants.msgs.NonExistingSalesMsg')]], 500);
        }

        return \Response::json(['response' => $show,'error' => 
            ['code' => Config::get('constants.codes.OkCode'), 
            'msg' => Config::get('constants.msgs.OkMsg')]], 200);
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
        return \Response::json(['response' => $show,'error' => 
            ['code' => Config::get('constants.codes.NonExistingEventCode'), 
            'msg' => Config::get('constants.msgs.NonExistingEventMsg')]], 500);
        }

        $my_volunteers = $show -> volunteers -> lists('id') -> toArray();

        $volunteers = Volunteer::orderBy('name','DESC') -> lists('name','id');
        
        /*return view('')
        -> with('show', $show)
        -> with('my_volunteers', $my_volunteers)
        -> with('volunteers',$volunteers);*/
        
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
        if(!$request -> name && !$request -> schedule && !$request -> description && !$request->file('image')
            && !$request -> volunteer_id){
                return \Response::json(['response' => '','error' => 
                    ['code' => Config::get('constants.codes.MissingInputCode'), 
                    'msg'   => Config::get('constants.msgs.MissingInputMsg')]], 500);
        }
            
        else{
            $show = Show::find($id);

            $update = array();
            try{
                if(!empty($request -> volunteer_id)){
                    $volunteer = Volunteer::find($request -> volunteer_id);
                    
                    if(empty($volunteer)){
                        return \Response::json(['response' => '', 'error' => 
                            ['code' => Config::get('constants.codes.NonExistingVolunteerCode'), 
                            'msg'   => Config::get('constants.msgs.NonExistingVolunteerMsg')]], 500);
                    }
                    $show -> volunteers() -> attach($request -> volunteer_id);
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
                    $name = $request -> name . '.' . $file->getClientOriginalExtension();
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
                return \Response::json(['response' => '','error' => 
                    ['code' => Config::get('constants.codes.InternalErrorCode'), 
                    'msg' => Config::get('constants.msgs.InternalErrorMsg')]], 500);
            }
        
            return \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.OkCode'), 
                'msg' => Config::get('constants.msgs.OkMsg')]], 200);
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
        $show = Show::find($id);
        $show -> delete();

        return \Response::json(['response' => '','error' => 
            ['code' => Config::get('constants.codes.OkCode'), 
            'msg' => Config::get('constants.msgs.OkMsg')]], 200);
    }
}
