<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileFieldsTypes extends Model
{
    //use HasFactory;
    protected $fillable = [
        'name', 'default'
    ];
    // Скрывает поля
    protected $hidden = ['created_at', 'updated_at'];
}
