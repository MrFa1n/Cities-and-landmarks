<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftModel extends Model
{
    protected $fillable = [
        'name','desc','icon','tier','extra'
    ];
}
