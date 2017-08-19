<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model {
    
    protected $table = 'sales';

    protected $fillable = ['name','description','price','date', 'image'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
     protected $hidden = ['updated_at'];
}
