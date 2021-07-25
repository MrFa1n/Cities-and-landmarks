<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MatchModel extends Model
{
    protected $fillable = [
        'id', 'initiator_id','target_id'
    ];
}
