<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'restaurant_id',
        'user_id',
        'rating',
        'title',
        'content'
      ];
    
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function restaurant(){
        return $this->belongsTo('App\Restaurant');
    }

}
