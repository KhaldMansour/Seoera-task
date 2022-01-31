<?php

namespace App\Http\Controllers\Admin;

use App\Events\Chat;
use App\Models\Message;
use App\Models\Admin;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class MessageController extends Controller
{

    public $guard;

    public function __construct()
    {
        $this->guard = "admin";

        auth()->setDefaultDriver($this->guard);
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
        $admin = auth('admin')->user();

        $chat_room = ChatRoom::findOrFail($chat_room_id);
        
        if ( ($chat_room->first_entity == "admin" && $chat_room->first_entity_id == $admin->id) 
        || ($chat_room->second_entity == "admin" && $chat_room->second_entity_id == $admin->id) )
        {
            $messages = $chat_room->messages;

            return view('admin.chat', [
                'chat_room' => $chat_room,
                'messages' => $messages
                ]);
        }
        else{
            return abort(403, 'Something went wrong');;
        } 
    }
}
