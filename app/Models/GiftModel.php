<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftModel extends Model
{
    protected $fillable = [
        'id','name','desc','icon','tier','extra'
    ];
    // Скрывает поля
    protected $hidden = ['created_at', 'updated_at'];
}
