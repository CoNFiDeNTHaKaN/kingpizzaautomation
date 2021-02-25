<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuOption extends Model
{
    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'selected' => 'boolean',
        'additional_charge' => 'float',
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'name',
        'description',
        'menu_option_group_id',
        'additional_charge',
        'selected'
    ];
}
