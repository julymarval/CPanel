<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Event;
use App\Sale;
use App\Volunteer;
use App\Sponsor;
use App\Show;
use App\User;
use Config;

class AdminController extends Controller
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
        //$this->middleware('jwt.auth',['except' => ['index', 'show']]);
        $this->middleware('auth');
    }
    

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function indexEvents()
    {
        $events = Event::orderBy(Config::get('constants.fields.IdField'),'DESC')->paginate(5);
        
        if(empty($events)){
                    
            $code = Config::get('constants.codes.NonExistingEventCode'); 
            $msg = Config::get('constants.msgs.NonExistingEventMsg');

            return view('events.admin_event') 
            -> with('code', $code)
            -> with('msg',$msg);
        }

        $code = Config::get('constants.codes.OkCode');
        $msg = Config::get('constants.msgs.OkMsg');

        return view('events.admin_event')
        -> with('code', $code)
        -> with('msg', $msg)
        -> with('events', $events);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function indexSales()
    {
        $sales = Sale::orderBy(Config::get('constants.fields.IdField'),'DESC')->paginate(5);

        if(empty($sales)){
            
            return view('sales.admin_sale');
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

        return view('sales.admin_sale')
        -> with('sales', $sales);
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function indexShows()
    {
        $shows = Show::orderBy(Config::get('constants.fields.IdField'),'DESC')->paginate(5);
        
        if(empty($shows)){
            
            return view('shows.admin_show');
        }
        
        return view('shows.admin_show')
        -> with('shows', $shows);
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function indexSponsors()
    {
        $sponsors = Sponsor::orderBy(Config::get('constants.fields.IdField'),'DESC') -> paginate(5);

        if(empty($sponsors)){

            return view('sponsors.admin_sponsor');
        }

        return view('sponsors.admin_sponsor') 
        -> with('sponsors', $sponsors);
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function indexVolunteers()
    {
        $volunteers = Volunteer::orderBy(Config::get('constants.fields.IdField'),'DESC')->paginate(5);
        
        if(empty($volunteers)){

            return view('volunteers.admin_volunteer');
        }

        return view('volunteers.admin_volunteer')
        -> with('volunteers', $volunteers);
    }
 
     
}
