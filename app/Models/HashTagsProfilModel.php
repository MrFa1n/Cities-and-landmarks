<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HashTagsProfilModel extends Model
{
    protected $fillable = [
        'hashtag', 'profile_id'
    ];
}
