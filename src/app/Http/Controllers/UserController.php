<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use App\User;
use App\Event;
use App\Sale;
use Config;

class UserController extends Controller
{
    
    private $sales, $events;

    public function __construct() {
        
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        //$this->middleware('jwt.auth');
        $this->middleware('auth',['except' => ['create']]);
        $this -> events = Event::orderBy(Config::get('constants.fields.IdField'),'DESC')->paginate(5);
        $this -> sales = Sale::orderBy(Config::get('constants.fields.IdField'),'DESC')->paginate(5);
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
            $code = Config::get('constants.codes.NonExistingAdminCode'); 
            $msg = Config::get('constants.msgs.NonExistingAdminMsg');

            return view('users.index_user')
            -> with('user', $users)
            -> with('code', $code)
            -> with('msg', $msg);
        }
        
        $code = Config::get('constants.codes.OkCode');
        $msg = Config::get('constants.msgs.OkMsg');

            return view('users.index_user')
            -> with('code', $code)
            -> with('msg', $msg)
            -> with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create_user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$user = JWTAuth::toUser($request -> input('Authorization'));
        
        if (!is_array($request->all())) {
            $code = Config::get('constants.codes.MissingInputCode'); 
            $msg = Config::get('constants.msgs.MisingInputMsg');

            return view('admin_dashboard')
            ////-> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
        }
        
        $rules = [
            'name'      => 'required|min:4|max:20',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8|max:10',
            ];

        try {
            
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = Config::get('constants.codes.InvalidInputCode'); 
                $msg = Config::get('constants.msgs.InvalidInputMsg') . ": " .  $validator->errors();

                return view('admin_dashboard')
                ////-> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events)
                -> with('code', $code)
                -> with('msg', $msg);
            }

            $user = new User($request->all());

            $data = User::find($user->email);

            if(!empty($data)){
                $code = Config::get('constants.codes.ExistingAdminCode'); 
                $msg = Config::get('constants.msgs.ExistingAdminMsg');

                return view('admin_dashboard')
                ////-> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events)
                -> with('code', $code)
                -> with('msg', $msg);
            }

            $user->password = bcrypt($request->password);
            $user -> save();
            $code = Config::get('constants.codes.OkCode');
            $msg = Config::get('constants.msgs.OkMsg');

            return view('admin_dashboard')
           // -> with('user', $user -> name)
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
            
        } catch (Exception $e) {
            \Log::info('Error creating user: '.$e);
            $code = Config::get('constants.codes.InternalErrorCode'); 
            $msg = Config::get('constants.msgs.InternalErrorMsg');

            return view('admin_dashboard')
            ////-> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
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
            $code = Config::get('constants.codes.NonExistingSalesCode'); 
            $msg = Config::get('constants.msgs.NonExistingSalesMsg');

            return view('users.show_user')
            -> with('user', $user)
            -> with('code', $code)
            -> with('msg', $msg);
        }

        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');

        return view('users.show_user')
        -> with('code', $code)
        -> with('msg', $msg)
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
            $code = Config::get('constants.codes.NonExistingEventCode'); 
            $msg = Config::get('constants.msgs.NonExistingEventMsg');

            return view('users.edit_user')
            -> with('user', $user)
            -> with('code', $code)
            -> with('msg', $msg);
        }

        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');

        return view('users.edit_user')
        -> with('code', $code)
        -> with('msg', $msg)
        -> with('user', $user);
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
        //$user = JWTAuth::toUser($request -> input('Authorization'));
        
        if(!$request -> name){
            $code = Config::get('constants.codes.MissingInputCode'); 
            $msg = Config::get('constants.msgs.MissingInputMsg');

            return view('admin_dashboard')
            ////-> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
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
                $code = Config::get('constants.codes.InternalErrorCode'); 
                $msg = Config::get('constants.msgs.InternalErrorMsg');

                return view('admin_dashboard')
                ////-> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events)
                -> with('code', $code)
                -> with('msg', $msg);
            }
        
            $code = Config::get('constants.codes.OkCode'); 
            $msg = Config::get('constants.msgs.OkMsg');

            return view('admin_dashboard')
            //-> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
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
        //$user = JWTAuth::toUser($request -> input('Authorization'));
        
        $user = User::find($id);
        $user -> delete();

        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');

        return view('admin_dashboard')
        //-> with('user', $user -> name) 
        -> with('sales', $this -> sales)
        -> with('events', $this -> events)
        -> with('code', $code)
        -> with('msg', $msg);
    }
}
