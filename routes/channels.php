<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('messages.{chat_room_id}', function ($user, $chat_room_id) {
    $chat_room = \App\Models\ChatRoom::find($chat_room_id);

    if (auth()->guard('admin')->user())
    {
        return ( ($chat_room->first_entity == "admin" && $user->id == $chat_room->first_entity_id) 
            || ($chat_room->second_entity == "admin" && $user->id == $chat_room->second_entity_id) );
    }
    if (auth()->guard('user')->user())
    {
        return ( ($chat_room->first_entity == "user" && $user->id == $chat_room->first_entity_id) 
            || ($chat_room->second_entity == "user" && $user->id == $chat_room->second_entity_id) );
    }
} , ['guards' => ['admin' , 'user']] );
