<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftModel extends Model
{
    protected $fillable = [
        'initiator_id','target_id','description','icon','type_of_gift'
    ];
}
