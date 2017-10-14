<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
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
        $this->middleware('auth');
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
            $code = Config::get('constants.codes.NonExistingUserCode'); 
            $msg = Config::get('constants.msgs.NonExistingUserMsg');

            return view('users.user')
            -> with('user', $users)
            -> with('code', $code)
            -> with('msg', $msg);
        }
        
        $code = Config::get('constants.codes.OkCode');
        $msg = Config::get('constants.msgs.OkMsg');

            return view('users.user')
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
        $user = Auth::user();
        
        $email = Config::get('constants.emails.Admin');
        
        if($user -> email != $email){

            $code = Config::get('constants.codes.InvalidAdminCode'); 
            $msg = Config::get('constants.msgs.InvalidAdminMsg');

            return redirect() -> route('dashboard') 
            -> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
        }
        else{
            return view('auth.register');
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
            $code = Config::get('constants.codes.NonExistingUserCode'); 
            $msg = Config::get('constants.msgs.NonExistingUserMsg');

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
        $admin = Auth::user();

        $email = Config::get('constants.emails.Admin');

        if($admin -> email != $email){
            $code = Config::get('constants.codes.InvalidAdminCode'); 
            $msg = Config::get('constants.msgs.InvalidAdminMsg');

            return redirect() -> route('dashboard') 
            -> with('user', $admin -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
        }
        else{
            if(empty($user)){
                $code = Config::get('constants.codes.NonExistingUserCode'); 
                $msg = Config::get('constants.msgs.NonExistingUserMsg');

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
        $user = User::find($id);

        if(!$request -> name && !$request -> email && !$request -> password){
            $code = Config::get('constants.codes.MissingInputCode'); 
            $msg = Config::get('constants.msgs.MissingInputMsg');

            return redirect() -> route('dashboard') 
            -> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
        }
        
        else{
            $update = array();
            try{
                if(!empty($request -> name)){
                    $update['name'] = $request -> name;
                }

                if(!empty($request -> password)){
                    $update['password'] = bcrypt($request -> password);
                }

                if(!empty($request -> email)){
                    $update['email'] = bcrypt($request -> email);
                }

                $user -> update($update);
            }
            catch(QueryException $e){
                \Log::error('Error updating show: '.$e);
                $code = Config::get('constants.codes.InternalErrorCode'); 
                $msg = Config::get('constants.msgs.InternalErrorMsg');

                return redirect() -> route('dashboard') 
                -> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events)
                -> with('code', $code)
                -> with('msg', $msg);
            }
        
            $code = Config::get('constants.codes.OkCode'); 
            $msg = Config::get('constants.msgs.OkMsg');

            return redirect() -> route('dashboard') 
            -> with('user', $user -> name) 
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
                
        $user = User::find($id);
        $admin = Auth::user();
        
        $email = Config::get('constants.emails.Admin');

        if($admin -> email != $email){
            $code = Config::get('constants.codes.InvalidAdminCode'); 
            $msg = Config::get('constants.msgs.InvalidAdminMsg');

            return redirect() -> route('dashboard') 
            -> with('user', $admin -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
        }
        else{
            $user -> delete();

            $code = Config::get('constants.codes.OkCode'); 
            $msg = Config::get('constants.msgs.OkMsg');

            return redirect() -> route('dashboard') 
            -> with('user', $admin -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
        }
    }
}
