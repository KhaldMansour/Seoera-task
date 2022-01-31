<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ChatRoomController extends Controller
{

    public $guard;

    public function __construct()
    {
        $this->guard = "admin";

        auth()->setDefaultDriver($this->guard);
    }

    public function findChatRoom(Request $request)
    {
        $admin = auth('admin')->user();

        $entity = $request->entity;
        
        $entity_id = $request->entity_id;

        if ($entity == "user")
        {
            $admin_rooms = DB::table('chat_rooms')->where('first_entity' , $this->guard)->where('first_entity_id' , $admin->id)->get();
            if ($admin_rooms)
            {
                $matched_room = $admin_rooms->where('second_entity' , $entity)->where("second_entity_id" , $entity_id)->first();
                if($matched_room){
                    $matched_room_id = $matched_room->id;
                }else
                {
                    $matched_room_id = DB::table('chat_rooms')->insertGetId([
                        "first_entity" => $this->guard,
                        "first_entity_id" => $admin->id,
                        "second_entity" => $entity,
                        "second_entity_id"  => $entity_id,
                        'created_at'    => Carbon::now()->toDateTimeString(),
                        'updated_at'    => Carbon::now()->toDateTimeString()
                    ]);
                }
            }
        }else
        {
            $matched_room = DB::table('chat_rooms')->where('first_entity' , $this->guard)->where('first_entity_id' , $admin->id)->where('second_entity' , $entity)->where("second_entity_id" , $entity_id)->first();

            if($matched_room)
            {
                $matched_room_id = $matched_room->id;
            }
            else if ($matched_room = DB::table('chat_rooms')->where('first_entity' , $this->guard)->where('first_entity_id' , $entity_id)->where('second_entity' , $entity)->where("second_entity_id" ,  $admin->id)->first())
            {
                $matched_room_id = $matched_room->id;
            }
            else
            {
                $matched_room_id = DB::table('chat_rooms')->insertGetId([
                    "first_entity" => $this->guard,
                    "first_entity_id" => $admin->id,
                    "second_entity" => $entity,
                    "second_entity_id"  => $entity_id,
                    'created_at'    => Carbon::now()->toDateTimeString(),
                    'updated_at'    => Carbon::now()->toDateTimeString()
                ]);
            }
        }

        return \Redirect::route('admin.getmessages', ['id' => $matched_room_id]);
    }
}
