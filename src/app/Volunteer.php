<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model {
    
    protected $table = 'volunteers';

    protected $fillable = ['name','status','description','image', 'phone'];

    public function events(){
        return $this -> belongsToMany('App\Event')->withTimestamps();
    }

    public function sponsor(){
        return $this -> hasMany('App\Sponsor');
    }

    public function shows(){
        return $this -> belongsToMany('App\Show')->withTimestamps();
    }
}
