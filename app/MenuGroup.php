<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MenuGroup extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'items'];

    protected $with = [
        'items'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $append = [
        'slug'
    ];

    public function items()
    {
        return $this->hasMany('App\MenuItem');
    }

    public function getSlugAttribute()
    {
        return Str::slug($this->attributes['name']);
    }
}
