<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $user = new User();
        $user->name = 'Test User1'; 
        $user->email = 'test1@gmail.com';
        $user->password = bcrypt('password');
        $user->save();

        $user = new User();
        $user->name = 'Test User2'; 
        $user->email = 'test2@gmail.com';
        $user->password = bcrypt('password');
        $user->save();

        $user = new User();
        $user->name = 'Test User3'; 
        $user->email = 'test3@gmail.com';
        $user->password = bcrypt('password');
        $user->save();
    }
}
