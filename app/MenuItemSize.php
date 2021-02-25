<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItemSize extends Model
{
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'menu_item_id',
        'label',
        'additional_charge',
        'price'
    ];

    protected $casts = [
        'additional_charge' => 'float',
    ];

    public function setPriceAttribute($value)
    {
        $this->attributes['additional_charge'] = $value;
    }
}
