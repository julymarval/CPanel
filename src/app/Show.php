<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Show extends Model {
    
    protected $table = 'shows';

    protected $fillable = ['name','description','schedule','image'];

    public function volunteers(){
        return $this -> belongsToMany('App\Volunteer')->withTimestamps();
    }
}
