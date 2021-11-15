<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Premium extends Model
{
    protected $fillable = [
        'profile_id', 'prem_flag', 'purchase_date', 'expiration_date'
    ];
    
}
