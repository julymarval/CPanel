<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model {
    
    protected $table = 'volunteers';

    protected $fillable = ['name','status','description','image'];

    public function Events(){
        return $this -> belongsToMany('App\Event')->withTimestamps();
    }

    public function Sponsors(){
        return $this -> hasOne('App\Sponsor');
    }

    public function Shows(){
        return $this -> belongsToMany('App\Shows')->withTimestamps();
    }
}
