<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $myArray = [];
        foreach ($this->collection as $item){
            $user = User::find($item->id);
            $myArray[] = [
                "message_count" =>  $item->message,
                "id"            =>  $item->id,
                "name"          =>  $user->name,
                "avatar"        =>  $user->avatar,
                "visit_count"   =>  $item->visited,
                "last_time"     =>  $item->last_time,
            ];
        }

        return $myArray;
        #return parent::toArray($request);
    }
}
