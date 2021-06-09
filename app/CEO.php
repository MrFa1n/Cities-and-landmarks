<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CEO extends Model
{
    protected $fillable = [
        'name', 'sex', 'year', 'company_headquarters', 'what_company_does'
    ];
}
