<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{ 
    protected $fillable = [
        'avatar', 
        'name',
        'created'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function getAvatar(){
        return $this->user->avatar;
    }
    public function getName(){
        return $this->user->name;
    }
    public function getDate(){
        return $this->user->created;
    }
}
