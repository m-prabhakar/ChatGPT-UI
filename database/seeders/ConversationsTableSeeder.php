<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConversationsTableSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Conversation::factory(5)->create();  // Create 5 sample conversations
    }
    
}
