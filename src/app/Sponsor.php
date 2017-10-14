<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model {
    
    protected $table = 'sponsors';

    protected $fillable = ['name','description','level','status','image','address','link','volunteer_id'];

    public function events(){
        return $this -> belongsToMany('App\Event')->withTimestamps();
    }

    public function volunteers(){
        return $this -> belongsTo('App\Volunteer');
    }

}
