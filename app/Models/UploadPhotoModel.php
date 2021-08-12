<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadPhotoModel extends Model
{
    protected $fillable = [
        'profile_id', 'photo'
    ];
}
