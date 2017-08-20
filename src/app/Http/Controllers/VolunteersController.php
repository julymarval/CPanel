<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use App\Volunteer;
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
        $volunteers = Volunteer::orderBy(Config::get('constants.fields.VolunteersIdField'),'ASC')->paginate(5);
        
        if(empty($volunteers)){
            return \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.NonExistingShowsCode'), 
                'msg' => Config::get('constants.msgs.NonExistingShowsMsg')]], 500);
        }
        
        return \Response::json(['response' => $volunteers,'error' => 
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
            'name'     => 'required|min:2|max:80|unique:volunteers',
        ];

        try {
            
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return \Response::json(['response' => '','error' => 
                    ['code' => Config::get('constants.codes.InvalidInputCode'), 
                    'msg' => Config::get('constants.msgs.InvalidInputMsg') . ': ' .
                    $validator->errors()]], 500);
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
        $volunteer = DB::table(Config::get('constants.tables.VolunteersTable'))
        ->where(Config::get('constants.fields.VolunteersIdField'), $id)->first();
    
        if(empty($volunteer)){
            return \Response::json(['response' => $volunteer,'error' => 
                ['code' => Config::get('constants.codes.NonExistingVolunteerCode'), 
                'msg' => Config::get('constants.msgs.NonExistingVolunteerMsg')]], 500);
        }

        return \Response::json(['response' => $volunteer,'error' => 
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
            return \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.MissingInputCode'), 
                'msg'   => Config::get('constants.msgs.MissingInputMsg')]], 500);
        }

        else{
            $volunteer = DB::table(Config::get('constants.tables.VolunteersTable'))
            ->where(Config::get('constants.fields.VolunteersIdField'), $id) -> first();

            $update = array();
            try{
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

                DB::table(Config::get('constants.tables.VolunteersTable'))
                ->where(Config::get('constants.fields.VolunteersIdField'), $id)
                ->update($update);
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
        DB::table(Config::get('constants.tables.VolunteersTable'))
        ->where(Config::get('constants.fields.VolunteersIdField'), $id)
        ->delete();

        return \Response::json(['response' => '','error' => 
            ['code' => Config::get('constants.codes.OkCode'), 
            'msg' => Config::get('constants.msgs.OkMsg')]], 200);
    }
}
