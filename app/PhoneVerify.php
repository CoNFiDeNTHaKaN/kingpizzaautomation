<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhoneVerify extends Model
{
    protected $fillable = [
        'user_id',
        'code'
      ];
}
