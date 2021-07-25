<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuteBoxModel extends Model
{
    protected $fillable = [
        'name','description','price','type_of_lutebox','extra'
    ];
}
