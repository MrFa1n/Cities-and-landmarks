<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuteBoxModel extends Model
{
    protected $fillable = [
        'type','active','name','desc','extra','tier'
    ];
}
