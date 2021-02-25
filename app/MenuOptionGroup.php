<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuOptionGroup extends Model
{
    protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'name',
        'description',
        'menu_item_id',
        'required',
        'allow_multiple',
        'multiple_limit'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];

    protected $with = [
        'option_values'
    ];

    protected $casts = [
        'required' => 'boolean',
        'allow_multiple' => 'boolean',
        'multiple_limit' => 'number'
    ];

    public function option_values()
    {
        return $this->hasMany('App\MenuOption');
    }
}
