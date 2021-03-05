<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;
use Cookie;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Restaurant extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $casts = [
    'opening_hours' => 'array',
    'order_hours' => 'array',
    'delivery_hours' => 'array',
    'flags' => 'array',
    'favourites' => 'array',
    'delivery_fee' => 'float',
    'delivery_minimum' => 'float',
    'delivery_range' => 'float',
    'service_charge' => 'float',
    'discount_percentage' => 'float',

  ];

    protected $attributes = [
    'logo' => 1,
    'cover_image' => 1
  ];


    protected $hidden = [
    'lat',
    'lng'
  ];

    protected $fillable = [
      'user_id',
      'name',
      'slug',
      'description',
      'allergy_info',
      'cover_image',
      'logo',
      'facebook_url',
      'twitter_url',
      'youtube_url',
      'contact_number',
      'opening_hours',
      'order_hours',
      'delivery_hours',
      'hygiene_rating',
      'collection_lead_time',
      'delivery_lead_time',
      'address_line_1',
      'address_line_2',
      'address_city',
      'address_county',
      'address_postcode',
      'lat',
      'lng',
      'delivery_range',
      'delivery_minimum',
      'delivery_fee',
      'service_charge',
      'discount_percentage',
      'flags',
      'favourites'
  ];

    protected $appends = ['open_now','order_now', 'logo', 'cover', 'rating', 'rating_count'];

    protected $with = ['menu'];

    public function scopeWithinRange($query, $lat, $lng)
    {
        return $query->select(
            DB::raw("*, (
            3959 * acos(
                cos( radians(  ?  ) ) *
                cos( radians( lat ) ) *
                cos( radians( lng ) - radians(?) ) +
                sin( radians(  ?  ) ) *
                sin( radians( lat ) )
            )
       ) AS distance")
        )
    ->havingRaw('distance < delivery_range')
    ->orderBy("distance")
    ->setBindings([$lat, $lng, $lat]);
    }

    public function scopePostCode($query, $post_code)
    {
        if($post_code[4]!=" "){
            $post_code[5]=$post_code[4];
            $post_code[4]=" ";
        }
        $post_code=substr($post_code , 0 , 6);
        $post_codes=PostCode::where('post_code' , $post_code)->get();
        $restaurants=$query->get()->filter(function ($item) use ($post_codes){
            foreach($post_codes as $postcode){
                if($item->id == $postcode->restaurant_id)
                return true;
            }
            return false;
        });
        return $restaurants;
    }

    public function setNameAttribute($value)
    {
        $city = Str::slug($this->attributes['address_city']);
        $this->attributes['slug'] = Str::slug($value, '-') . "-{$city}";
        $this->attributes['name'] = $value;
    }

    public function getRatingAttribute()
    {
        $ratings=0;
        
        foreach($this->ratings as $rate){
            $ratings+=$rate->rating;
        }
        if(count($this->ratings)==0)
            $ratings=0;
        else
            $ratings/=count($this->ratings);
        return number_format($ratings,2);
    }


    public function getRatingCountAttribute()
    {
        return count($this->ratings);
    }

    public function getOpenNowAttribute()
    {
        $today = $this->opening_hours[(Date('w'))];
        $timeNow = Date('Hi');
        if ($timeNow < $today[0] || $timeNow > $today[1]) {
            return false;
        }
        return true;
    }

    public function getOrderNowAttribute()
    {
        $today = $this->order_hours[(Date('w'))];
        $timeNow = Date('Hi');
        if ($timeNow < $today[0] || $timeNow > $today[1]) {
            return false;
        }
        return true;
    }

    public function getDeliveryNowAttribute()
    {
        $today = $this->delivery_hours[(Date('w'))];
        $timeNow = Date('Hi');
        if ($timeNow < $today[0] || $timeNow > $today[1]) {
            return false;
        }
        return true;
    }
    

    public function getLogoAttribute()
    {
        if ($this->hasMedia('logo')) {
            return $this->getMedia('logo')->last()->getUrl();
        }
        return null;
    }

    public function getCoverAttribute()
    {
        if ($this->hasMedia('cover')) {
            $mediaItems = $this->getMedia('cover');
            $urls=array();
            foreach($mediaItems as $media){
                array_push($urls,$media->getUrl());
            }
            return $urls;
        }
        return [];
    }

    public function menu()
    {
        return $this->hasMany('App\MenuGroup');
    }

    public function ratings()
    {
        return $this->hasMany('App\Rating');
    }
	
	public function formatted_hours($time){

		$hour=$time[0].$time[1];
		if($hour>12){
			$hour=$hour-12;
			return $hour.'.'.$time[2].$time[3].' pm';
		}else{
			return $hour.'.'.$time[2].$time[3].' am';
		}
						
	}

    public function getDeliveryFeeAttribute(){
        $post_codes=PostCode::where('restaurant_id',$this->id)->get();
        $post_code=Cookie::get('eko_postcode');
        if(count($post_codes)==0){
            return $this->attributes['delivery_fee'];
        }else{
            return $post_codes->first(function($item) use ($post_code){
                if($post_code[4]!=" "){
                    $post_code[5]=$post_code[4];
                    $post_code[4]=" ";
                }
                $post_code=substr($post_code , 0 , 6);
                $post_code=strtolower($post_code);
                return $item->post_code==$post_code;
            })->delivery_fee ?? $this->attributes['delivery_fee'];
        }
    }
}
