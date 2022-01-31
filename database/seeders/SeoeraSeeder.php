<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SeoeraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "Test User",
            'email' => 'test@user.com',
            'password' => Hash::make('123456789'),
        ]);

        DB::table('admins')->insert([
            'name' => "Test Admin",
            'email' => 'test@admin.com',
            'password' => Hash::make('123456789'),
        ]);

        DB::table('chat_rooms')->insert([
            'first_entity' => "admin",
            'first_entity_id' => 1,
            'second_entity' => "user",
            'second_entity_id' => 1,
            'created_at'    => Carbon::now()->toDateTimeString(),
            'updated_at'    => Carbon::now()->toDateTimeString()
        ]);

        DB::table('messages')->insert([
            'message' => 'this is the test first message',
            'sender' => 'admin',
            'sender_id' => 1,
            'receiver' => 'admin',
            'receiver_id' => 1,
            'chat_room_id' => 1,
            'created_at'    => Carbon::now()->toDateTimeString(),
            'updated_at'    => Carbon::now()->toDateTimeString()
            ]);
    }
}
