<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use App\Sale;
use Config;

class SalesController extends Controller
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
        $sales = Sale::orderBy(Config::get('constants.fields.IdField'),'ASC')->paginate(5);

        if(empty($sales)){
            $code = Config::get('constants.codes.NonExistingSalesCode');
            $msg = Config::get('constants.msgs.NonExistingSalesMsg');
            
            return view('') 
            -> with('code', $code)
            -> with('msg', $msg);
        }
        
        $code = Config::get('constants.codes.OkCode');
        $msg = Config::get('constants.msgs.OkMsg');

        return view('') 
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
        if (empty($request -> name) || (empty($request -> price))) {
            $code = Config::get('constants.codes.MissingInputCode'); 
            $msg = Config::get('constants.msgs.MissingInputMsg');

            return view('') 
            -> with('code', $code)
            -> with('msg', $msg);
        }
        
        $rules = [
            'name' => 'required|min:2|max:80',
            'price'=> 'required',
        ];

        try {
            
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = Config::get('constants.codes.InvalidInputCode'); 
                $msg = Config::get('constants.msgs.InvalidInputMsg') . ': ' . $validator->errors();

                return view('') 
                -> with('code', $code)
                -> with('msg', $msg);
            }

            $sale = new Sale($request->all());

            $data = DB::table(Config::get('constants.tables.SalesTable'))
                ->where(Config::get('constants.fields.NameField'), $sale->name)->first();

            if(!empty($data)){
                $code = Config::get('constants.codes.ExistingSaleCode'); 
                $msg = Config::get('constants.msgs.ExistingSaleMsg');
                
                return view('') 
                -> with('code', $code)
                -> with('msg', $msg);;
            }

            if($request->file('image')){
                $file = $request -> file('image');
                $name = $request -> name . '.' . $file->getClientOriginalExtension();
                $path = public_path() . '/images/sales/';
                $file -> move($path,$name);
                $sale -> image = $name;
            }

            $sale -> save();
            $code = Config::get('constants.codes.OkCode'); 
            $msg = Config::get('constants.msgs.OkMsg');
            
            return view('') 
            -> with('code', $code)
            -> with('msg', $msg);
            
        } catch (Exception $e) {
            \Log::info('Error creating sale: '.$e);
            $code = Config::get('constants.codes.InternalErrorCode'); 
            $msg = Config::get('constants.msgs.InternalErrorMsg');

            return view('') 
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

            return view('') 
            -> with('code', $code)
            -> with('msg', $msg);
        }

        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');
        
        return view('') 
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

            return view('') 
            -> with('code', $code)
            -> with('msg', $msg);
        }

        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');

        return view('')
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
        if(!$request -> name && !$request -> price && !$request -> description && !$request->file('image')){
            $code = Config::get('constants.codes.MissingInputCode'); 
            $msg = Config::get('constants.msgs.MissingInputMsg');

            return view('') 
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
                
                if(!empty($request -> price)){
                    $update['price'] =  $request -> price;
                }
                
                if(!empty($request -> description)){
                    $update['description'] =  $request -> description;
                }
                if(!empty($request -> file('image'))){
                    $file = $request -> file('image');
                    $name = $request -> name . '.' . $file->getClientOriginalExtension();
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
                $code = Config::get('constants.codes.InternalErrorCode');
                $msg = Config::get('constants.msgs.InternalErrorMsg');

                return view('') 
                -> with('code', $code)
                -> with('msg', $msg);
            }
        
            $code = Config::get('constants.codes.OkCode'); 
            $msg = Config::get('constants.msgs.OkMsg');
            
            return view('') 
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
        $sale = Sale::find($id);
        $sale -> delete();

        $code = Config::get('constants.codes.OkCode'); 
        $msg = Config::get('constants.msgs.OkMsg');

        return view('') 
        -> with('code', $code)
        -> with('msg', $msg);
    }
}
