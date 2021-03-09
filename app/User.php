<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasMediaTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'first_name',
      'last_name',
      'email',
      'contact_number',
      'password',
      'stripe_customer_id',
      'stripe_card_id',
      'stripe_card_brand',
      'stripe_card_last4',
      'delivery_address_line1',
      'delivery_address_line2',
      'delivery_address_city',
      'delivery_address_county',
      'delivery_address_postcode',
      'phone_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'stripe_card_id', 'stripe_card_last4', 'stripe_customer_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
      'name',
      'is_restaurant'
    ];

    public function getNameAttribute() {
      return ($this->first_name . ' ' . $this->last_name);
    }

    public function orders() {
      return $this->hasMany('App\Order');
    }

    public function restaurant() {
      return $this->hasOne('App\Restaurant');
    }
	
	public function addresses() {
      return $this->hasMany('App\UserAddress');
    }

  public function baskets(){
    $this->hasMany('App\Basket');
  }

    public function getIsRestaurantAttribute() {
      return (bool) $this->restaurant()->exists();
    }

    public function phoneVerify(){
      return $this->hasOne('App\PhoneVerify');
    }
}
