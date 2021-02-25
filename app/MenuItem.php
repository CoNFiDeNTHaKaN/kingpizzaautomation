<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'restaurant_id',
        'name',
        'description',
        'base_price'
    ];

    protected $with = [
        'flags',
        'options',
        'sizes'
    ];

    protected $casts = [
        'base_price' => 'float',
    ];

    public function flags()
    {
        return $this->hasMany('App\ItemFlag');
    }

    public function options()
    {
        return $this->hasMany('App\MenuOptionGroup');
    }

    public function sizes()
    {
        return $this->hasMany('App\MenuItemSize');
    }

    public function getSizesAttribute()
    {
        return '';
    }
}
