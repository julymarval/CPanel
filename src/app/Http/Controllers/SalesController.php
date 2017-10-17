<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Sale;
use App\Event;
use App\Sponsor;
use Config;

class SalesController extends Controller
{

    private $sales, $events, $sponsors;

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
        //$this->middleware('jwt.auth',['except' => ['index', 'show']]);
        $this->middleware('auth',['except' => ['index', 'show']]);
        $this -> events = Event::orderBy(Config::get('constants.fields.IdField'),'DESC')->paginate(5);
        $this -> sales = Sale::orderBy(Config::get('constants.fields.IdField'),'DESC')->paginate(5);
        $this -> sponsors = Sponsor::orderBy(Config::get('constants.fields.IdField'),'DESC')->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = Sale::orderBy(Config::get('constants.fields.IdField'),'DESC')->paginate(5);

        if(empty($sales)){
            $code = Config::get('constants.codes.NonExistingSalesCode');
            $msg = Config::get('constants.msgs.NonExistingSalesMsg');
            
            return view('sales.sale')
            -> with('shows', $this -> sponsors) 
            -> with('sales', $sales)
            -> with('code', $code)
            -> with('msg', $msg);
        }

        foreach($sales as $sale){
            $date = strtotime($sale -> created_at);
            $newformat = date('Y-m-d',$date);
            $today = date('Y-m-d',time());
            $january = new \DateTime($newformat);
            $february = new \DateTime($today);
            $interval = $february->diff($january);
            $days = $interval->format('%a');
            if($days > 6){
                $sale -> delete();
            }
        }
        
        $code = Config::get('constants.codes.OkCode');
        $msg = Config::get('constants.msgs.OkMsg');

        return view('sales.sale') 
        -> with('sponsors', $this -> sponsors) 
        -> with('code', $code)
        -> with('msg', $msg)
        -> with('sales', $sales);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sales.create_sale');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (empty($request -> name) || (empty($request -> price))) {
            
            flash('Name and price are required') -> error();
            return redirect() -> route('sales.create') 
            -> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
        }
        
        $rules = [
            'name' => 'required|min:2|max:80|regex:/^[a-zA-ZÑñ\s]+$/',
            'price'=> 'required',
        ];

        try {
            
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                
                flash('One or more value are wrong.') -> error();
                return redirect() -> route('sales.create') 
                -> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events)
                -> with('code', $code)
                -> with('msg', $msg);
            }

            $sale = new Sale($request->all());

            $data = DB::table(Config::get('constants.tables.SalesTable'))
                ->where(Config::get('constants.fields.NameField'), $sale->name)->first();

            if(!empty($data)){
               
                flash('This tiangui already exists.') -> error(); 
                return redirect() -> route('sales.create')
                -> with('user', $user -> name)  
                -> with('sales', $this -> sales)
                -> with('events', $this -> events);
            }

            if($request->file('image')){
                $file = $request -> file('image');
                $name = $request -> name . '.' . $file->getClientOriginalExtension();
                $path = public_path() . '/images/sales/';
                $file -> move($path,$name);
                $sale -> image = $name;
            }

            $sale -> save();
            
            flash('The tiangui has been created correctly.') -> success();
            return redirect() -> route('dashboard') 
            -> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events);
            
        } catch (Exception $e) {
            \Log::info('Error creating sale: '.$e);
            
            flash('Ops! An error has ocurred. Please try again.') -> error();
            return redirect() -> route('sales.index') 
            -> with('user', $user -> name) 
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
        $sale = Sale::find($id);
        
        if(empty($sale)){
            $code = Config::get('constants.codes.NonExistingSalesCode');
            $msg = Config::get('constants.msgs.NonExistingSalesMsg');

            return view('sales.show_sale') 
            -> with('sale', $sale)
            -> with('code', $code)
            -> with('msg', $msg);
        }

        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');
        
        return view('sales.show_sale') 
        -> with('code', $code)
        -> with('msg', $msg)
        -> with('sale', $sale);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sale = Sale::find($id);

        if(empty($sale)){
            $code = Config::get('constants.codes.NonExistingEventCode'); 
            $msg = Config::get('constants.msgs.NonExistingEventMsg');

            return view('sales.edit_sale') 
            -> with('sale', $sale)
            -> with('code', $code)
            -> with('msg', $msg);
        }

        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');

        return view('sales.edit_sale')
        -> with('code', $code)
        -> with('msg', $msg)
        -> with('sale', $sale);
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
        $user = Auth::user();

        if(!$request -> name && !$request -> price && !$request -> description 
            && !$request->file('image') && !$request -> phone){
            
            flash('At least one field is required.') -> error();
            return redirect() -> route('sales.edit',['id' => $id])
            -> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events)
            -> with('code', $code)
            -> with('msg', $msg);
        }

        else{
            $sale = Sale::find($id);
            
            $update = array();
            try{
                if(!empty($request -> name)){
                    $update['name'] = $request -> name;
                }

                if(!empty($request -> phone)){
                    $update['phone'] = $request -> phone;
                }
                
                if(!empty($request -> price)){
                    $update['price'] =  $request -> price;
                }
                
                if(!empty($request -> description)){
                    $update['description'] =  $request -> description;
                }
                if(!empty($request -> file('image'))){
                    $file = $request -> file('image');
                    $name = $sale -> name . '.' . $file->getClientOriginalExtension();
                    if(file_exists(public_path() . '/images/sales/' . $sale -> image)){
                        Storage::delete(public_path() . '/images/sales/' . $sale -> image);
                    }
                    $path = public_path() . '/images/sales/';
                    $file -> move($path,$name);
                    $update['image'] = $name;
                }

                $sale -> update($update);
            }
            catch(QueryException $e){
                \Log::error('Error creating sale: '.$e);
                
                flash('Ops! An error has ocurred. Please try again.') -> error();
                return redirect() -> route('sales.index') 
                -> with('user', $user -> name) 
                -> with('sales', $this -> sales)
                -> with('events', $this -> events)
                -> with('code', $code)
                -> with('msg', $msg);
            }
        
            flash('The tiangui has been updated correctly.') -> success();
            return redirect() -> route('dashboard') 
            -> with('user', $user -> name) 
            -> with('sales', $this -> sales)
            -> with('events', $this -> events);
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
        $user = Auth::user();

        $sale = Sale::find($id);
        $sale -> delete();

        flash('The tiangui has been deleted correctly.') -> success();
        return redirect() -> route('dashboard')
        -> with('user', $user -> name) 
        -> with('sales', $this -> sales)
        -> with('events', $this -> events);
    }
}
