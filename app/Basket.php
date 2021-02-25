<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{

  protected $casts = [
    'contents' => 'array'
  ];

  protected $fillable = [
    'hash',
    'contents',
    'restaurant_id',
    'notes',
    'fulfilment_method',
    'user_id'
  ];

  protected $attributes = [
    'contents' => "{}",
    'restaurant_id' => -1
  ];

  protected $hidden = [
    'hash'
  ];

  protected $appends = [
    'total',
    'item_total',
    'is_collection'
  ];

  protected $with = [
    'restaurant'
  ];

  public function getDiscountedTotalAttribute(){
    $rate=$this->restaurant->discount_percentage; 
    $total=$this->item_total;
    $discount=$total/100*$rate;
    $discounted_total=$total-$discount;
    $discounted_total+=$this->restaurant->service_charge;
    $fee = ($this->fulfilment_method === "collection") ? 0 : $this->restaurant->delivery_fee;
    $discounted_total+=$fee;

    $return=[
      'total' => number_format($total,2),
      'discounted_total' => number_format($discounted_total,2),
      'rate' => $rate,
      'discount' => number_format($discount,2),
      'service_charge' => number_format($this->restaurant->service_charge,2),
      'delivery_fee' => number_format($fee,2)
    ];

    return $return;
  }

  public function getTotalAttribute () {
    $fee = ($this->fulfilment_method === "collection") ? 0 : $this->restaurant->delivery_fee;
    return number_format(($this->item_total + $fee + $this->restaurant->service_charge),2);
  }

  public function getItemTotalAttribute () {
    $totalPrice = 0;
    foreach ($this->contents as $item) {
      $totalPrice += $item['total_price'];
    }
    return number_format($totalPrice,2);
  }

  public function getIsCollectionAttribute() {
    return ($this->fulfilment_method === "collection");
  }

  public function restaurant () {
    return $this->belongsTo('App\Restaurant');
  }

  public function user(){
    return $this->belongsTo('App\User');
  }

}
