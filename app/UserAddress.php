<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
	  'user_id',
	  'name',
      'address_line1',
	  'address_line2',
      'city',
      'postcode',
    ];
	
	public function user(){
		$this->belongsTo('App\User');
	}
}
