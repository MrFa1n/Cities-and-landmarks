<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\ChatEvent;
use App\Http\Resources\UserCollection;
use App\Http\Controllers\Controller;

class Chat extends Controller
{
    public function allUserMessageSent(){
        $msg = Message::selectRaw("select *from table");
        return new UserCollection($msg);
    }

    public function fetchMessages(User $user){
        return $user->load("messages");
    }
    
    public function sendMessage(){
        $user = User::find(request("name"));
        #return ['status' => 'Test!'];
        $newMessage = $user->messages()->create([
            "message"   => request("messageSend"),
            "visited"   => 0,
            "admin"     => request("admin")
        ]);
        broadcast(new ChatEvent($user, $newMessage));

        return ['status' => 'Message sent!'];
    }
}
