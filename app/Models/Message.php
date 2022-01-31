<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['message' , 'chat_room_id' , 'receiver' , 'receiver_id' , 'sender' , 'sender_id'];

    public function chat_room()
    {
        return $this->belongsTo(ChatRoom::class);
    }
}
