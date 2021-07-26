<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileGifts extends Model
{
    protected $fillable = [
        'gift_id','profile_id','extra'
    ];
}
