<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
    
    protected $fillable = ['name', 'event_id'];

    public function events(){
        return $this -> belongsTo('App\Event');
    }
}
