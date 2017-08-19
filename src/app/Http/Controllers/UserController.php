<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use App\User;

class UserController extends Controller
{
    
    public function __construct() {
        
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        $this->middleware('jwt.auth', ['except' => ['store']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            return \Response::json(['response' => '','error' => ['code' => 100, 'msg' => 'MisingInput']], 500);
        }
        
        $rules = [
            'name'      => 'required|min:4|max:20',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8|max:10',
            ];

        try {
            
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return \Response::json(['response' => '','error' => ['code' => 101, 'msg' => 'InvalidInput: ' . 
                 $validator->errors()]], 500);
            }

            $user = new User($request->all());

            $data = User::find($user->email);

            if(!empty($data)){
                return \Response::json(['response' => '', 'error' => [ 'code' => 102, 'msg' => "ExistingUser"]], 500);
            }

            $user->password = bcrypt($request->password);
            $user -> save();
            return \Response::json(['response' => '','error' => ['code' => 0, 'msg' => 'ok']], 200);
            
        } catch (Exception $e) {
            \Log::info('Error creating user: '.$e);
            return \Response::json(['response' => '','error' => ['code' => 999, 'msg' => 'InternalError']], 500);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
