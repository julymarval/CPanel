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
            
            $code = Config::get('constants.codes.NonExistingAdminCode');
            $msg = Config::get('constants.msgs.NonExistingAdminMsg');
           
            return view('users.login')
            -> with('code', $code, 401)
            -> with ('msg', $msg)
            -> with('token','');
        }

        if(!Hash::check($request -> password, $user -> password)){
            
            $code = Config::get('constants.codes.InvalidPasswordCode');
            $msg = Config::get('constants.msgs.InvalidPasswordMsg');
           
            return view('users.login')
            -> with('code', $code, 401)
            -> with ('msg', $msg)
            -> with('token','');
        }

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                
                $code = Config::get('constants.codes.InvalidCredentialsCode');
                $msg = Config::get('constants.msgs.InvalidCredentialsMsg');
               
                return view('users.login')
                -> with('code', $code, 401)
                -> with ('msg', $msg)
                -> with('token','');
            }
        } catch (JWTException $e) {
            
            $code = Config::get('constants.codes.InternalErrorCode');
            $msg = Config::get('constants.msgs.InternalErrorMsg');
           
            return view('users.login')
            -> with('code', $code, 500)
            -> with ('msg', $msg)
            -> with('token','');
        }
        $code = Config::get('constants.codes.OkCode');
        $msg = Config::get('constants.msgs.OkMsg');

        return view('users.login')
        -> with('code', $code)
        -> with ('msg', $msg)
        -> with('token',$token, 200);
    }
}
