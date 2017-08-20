<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use App\User;
use Config;

class AuthenticateController extends Controller {

    public function __construct() {
        
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        $this->middleware('jwt.auth', ['except' => ['authenticate']]);
    }
    
    public function index() {
        // TODO: show users
    }    

    public function authenticate(Request $request) {

        $credentials = $request->only('email', 'password');

        $user = DB::table(Config::get('constants.tables.UsersTable'))
        ->where(Config::get('constants.fields.EmailField'), $request -> email)->first();
        
        if(empty($user)){
            return response()->json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.NonExistingAdminCode'), 
                'msg' => Config::get('constants.msgs.NonExistingAdminMsg')]], 401);
        }

        if(!Hash::check($request -> password, $user -> password)){
            return response()->json(['response' => '','error' => 
                ['code' => Config::get('constants.codes.InvalidPasswordCode'), 
                'msg' => Config::get('constants.msgs.InvalidPasswordMsg')]], 401);
        }

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['response' => '','error' => ['code' => 100, 'msg' => 'InvalidCredentials']], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['response' => '','error' => ['code' => 999, 'msg' => 'InternalError']], 500);
        }

        // if no errors are encountered we can return a JWT
        return \Response::json(['response' => compact('token'),'error' => ['code' => 0, 'msg' => 'ok']], 200);
    }
}
