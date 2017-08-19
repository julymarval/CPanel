<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model {
    
    protected $table = 'sponsors';

    protected $fillable = ['name','description','level','status','volunteer_id'];

    public function Events(){
        return $this -> belongsToMany('App\Event')->withTimestamps();
    }

    public function Volunteers(){
        return $this -> belongsTo('App\Volunteer');
    }

}
