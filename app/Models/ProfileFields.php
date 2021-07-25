<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileFields extends Model
{
    //use HasFactory;
    protected $fillable = [
        'profile_id', 'field_type_id', 'value'
    ];
}
