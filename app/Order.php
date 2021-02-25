<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'desired_time' => 'datetime',
        'predicted_time' => 'datetime',
        'dispatched_time' => 'datetime',
        'paid' => 'boolean'
    ];


    protected $hidden = [
      'payment_id'
    ];

    protected $fillable = [
      'restaurant_id',
      'user_id',
      'basket_id',
      'desired_time',
      'predicted_time',
      'dispatched_time',
      'payment_type',
      'paid',
      'payment_id',
      'collection',
      'delivery_line1',
      'delivery_line2',
      'delivery_city',
      'delivery_county',
      'delivery_postcode',
	  'billing_line1',
	  'billing_line2',
	  'billing_city',
	  'billing_county',
	  'billing_postcode',
      'notes',
      'order_status_id',
    ];

    protected $with = [
      'restaurant',
      'user',
      'basket'
    ];

    protected $appends = [
      'fulfilment_method'
    ];

    public function restaurant() {
      return $this->belongsTo('App\Restaurant');
    }
    public function user() {
      return $this->belongsTo('App\User');
    }
    public function basket() {
      return $this->belongsTo('App\Basket');
    }

    public function getFulfilmentMethodAttribute () {
      return (($this->collection) ? "collection" : "delivery");
    }

}
