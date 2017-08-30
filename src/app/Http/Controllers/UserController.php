<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use App\User;
use Config;

class UserController extends Controller
{
    
    public function __construct() {
        
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        $this->middleware('jwt.auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy(Config::get('constants.fields.IdField'),'ASC')->paginate(5);
        
        if(empty($users)){
            $response = \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.NonExistingAdminCode'), 
                'msg' => Config::get('constants.msgs.NonExistingAdminMsg')]], 500);

            return view('') -> with('response', $response);
        }
        
        $response = \Response::json(['response' => $users,'error' => 
            ['code' => Config::get('constants.codes.OkCode'), 
            'msg' => Config::get('constants.msgs.OkMsg')]], 200);

        return view('') 
        -> with('response', $response)
        -> with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!is_array($request->all())) {
            $response = \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.MissingInputCode'), 
                'msg'   => Config::get('constants.msgs.MisingInputMsg')]], 500);

            return view('') -> with('response', $response);
        }
        
        $rules = [
            'name'      => 'required|min:4|max:20',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8|max:10',
            ];

        try {
            
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $response = \Response::json(['response' => '','error' => 
                    ['code' => Config::get('constants.codes.InvalidInputCode'), 
                    'msg'   => Config::get('constants.msgs.InvalidInputMsg') . ": " .  
                    $validator->errors()]], 500);

                return view('') -> with('response', $response);
            }

            $user = new User($request->all());

            $data = User::find($user->email);

            if(!empty($data)){
                $response = \Response::json(['response' => '', 'error' => 
                    [ 'code' => Config::get('constants.codes.ExistingAdminCode'), 
                    'msg'    => Config::get('constants.msgs.ExistingAdminMsg')]], 500);

                return view('') -> with('response', $response);
            }

            $user->password = bcrypt($request->password);
            $user -> save();
            $response = \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.OkCode'), 
                'msg'   => Config::get('constants.msgs.OkMsg')]], 200);

            return view('') -> with('response', $response);
            
        } catch (Exception $e) {
            \Log::info('Error creating user: '.$e);
            $response = \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.InternalErrorCode'), 
                'msg'   => Config::get('constants.msgs.InternalErrorMsg')]], 500);

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
        $user = User::find($id);
        
        if(empty($user)){
        $response = \Response::json(['response' => $show,'error' => 
            ['code' => Config::get('constants.codes.NonExistingSalesCode'), 
            'msg' => Config::get('constants.msgs.NonExistingSalesMsg')]], 500);

            return view('') -> with('response', $response);
        }

        $response = \Response::json(['response' => $user,'error' => 
            ['code' => Config::get('constants.codes.OkCode'), 
            'msg' => Config::get('constants.msgs.OkMsg')]], 200);

        return view('') 
        -> with('response', $response)
        -> with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        if(empty($user)){
            $response = \Response::json(['response' => '','error' => 
            ['code' => Config::get('constants.codes.NonExistingEventCode'), 
            'msg' => Config::get('constants.msgs.NonExistingEventMsg')]], 500);

            return view('') -> with('response', $response);
        }

        return view('') -> with('user', $user);
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
        if(!$request -> name){
            $response = \Response::json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.MissingInputCode'), 
                'msg'   => Config::get('constants.msgs.MissingInputMsg')]], 500);

            return view('') -> with('response', $response);
        }
        
        else{
            $user = User::find($id);

            $update = array();
            try{
                if(!empty($request -> name)){
                    $update['name'] = $request -> name;
                }

                if(!empty($request -> password)){
                    $update['password'] = bcrypt($request -> password);
                }

                $user -> update($update);
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
        $user = User::find($id);
        $user -> delete();

        $response = \Response::json(['response' => '','error' => 
            ['code' => Config::get('constants.codes.OkCode'), 
            'msg' => Config::get('constants.msgs.OkMsg')]], 200);

        return view('') -> with('response', $response);
    }
}
