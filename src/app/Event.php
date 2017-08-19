<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {
    
    protected $table = 'events';

    protected $fillable = ['name','description','date'];

    public function Sponsors(){
        return $this -> belongsToMany('App\Sponsor')->withTimestamps();
    }

    public function Volunteers(){
        return $this -> belongsToMany('App\Volunteer')->withTimestamps();
    }
}
