<?php

namespace App\Http\Controllers;

use App\Events\Chat;
use App\Models\Message;
use App\Models\User;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public $guard;

    public function __construct()
    {
        $this->guard = "user";

        $this->middleware('auth:' . $this->guard , ['except' => ['login', 'loginForm' , 'register' , 'create' ]]);
    }


    public function send(Request $request)
    {
        $this->validate($request, [
            'message' => 'required|alpha_num',
        ]);

        $data = request()->except(['_token']);

        $message = Message::forceCreate($data);

        event((new Chat($message))->dontBroadcastToCurrentUser());
    }

    public function getMessages($chat_room_id)
    {
        $user = auth('user')->user();

        $chat_room = ChatRoom::findOrFail($chat_room_id);
        
        if ( ($chat_room->first_entity == "user" && $chat_room->first_entity_id == $user->id) 
        || ($chat_room->second_entity == "user" && $chat_room->second_entity_id == $user->id) )
        {
            $messages = $chat_room->messages;

            return view('user.chat', [
                'chat_room' => $chat_room,
                'messages' => $messages
                ]);
        }
        else{
            return abort(403, 'Something went wrong');;
        } 
    }
}

