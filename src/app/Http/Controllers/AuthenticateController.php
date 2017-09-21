<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use App\User;
use App\Event;
use App\Sale;
use Config;

class AuthenticateController extends Controller {

    private $events, $sales;

    public function __construct() {
        
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        $this->middleware('jwt.auth', ['except' => ['authenticate']]);

        $this -> events = Event::orderBy(Config::get('constants.fields.IdField'),'DESC')->paginate(5);
        $this -> sales = Sale::orderBy(Config::get('constants.fields.IdField'),'DESC')->paginate(5);
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
           
            return view('admin_dashboard')
            -> with('code', $code, 401)
            -> with('user', " ")
            -> with ('msg', $msg)
            -> with('token','');
        }

        if(!Hash::check($request -> password, $user -> password)){
            
            $code = Config::get('constants.codes.InvalidPasswordCode');
            $msg = Config::get('constants.msgs.InvalidPasswordMsg');
           
            return view('admin_dashboard')
            -> with('code', $code, 401)
            -> with('user', '')
            -> with('sales', '')
            -> with('events', '')
            -> with ('msg', $msg)
            -> with('token','');
        }

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                
                $code = Config::get('constants.codes.InvalidCredentialsCode');
                $msg = Config::get('constants.msgs.InvalidCredentialsMsg');
               
                return view('admin_dashboard')
                -> with('user', '')
                -> with('code', $code, 401)
                -> with('sales', '')
                -> with('events', '')
                -> with ('msg', $msg)
                -> with('token','');
            }
        } catch (JWTException $e) {
            
            $code = Config::get('constants.codes.InternalErrorCode');
            $msg = Config::get('constants.msgs.InternalErrorMsg');
           
            return view('admin_dashboard')
            -> with('user', '')
            -> with('code', $code, 500)
            -> with('sales', '')
            -> with('events', '')
            -> with ('msg', $msg)
            -> with('token','');
        }
        $code = Config::get('constants.codes.OkCode');
        $msg = Config::get('constants.msgs.OkMsg');

        return view('admin_dashboard')
        -> with('user', $user->name)
        -> with('sales', $this -> sales)
        -> with('events', $this -> events)
        -> with('code', $code)
        -> with ('msg', $msg)
        -> with('token',$token, 200);
    }
}
