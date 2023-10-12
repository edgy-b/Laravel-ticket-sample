<?php

namespace Database\Seeders;

use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@mail.com',
            'password' => Hash::make('password'),
            'is_admin' => true
        ]);

        TicketType::factory()->create([
            'type' => 'ticket type 1'
        ]);
    }
}
