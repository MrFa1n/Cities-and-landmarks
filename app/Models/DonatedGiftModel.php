<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonatedGiftModel extends Model
{
    protected $fillable = [
        'id_gift','init','target','desc','extra'
    ];
}
