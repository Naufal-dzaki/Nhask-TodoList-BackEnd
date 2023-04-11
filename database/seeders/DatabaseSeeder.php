<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// use App\Http\Models\User;
use App\Models\User;
use App\Models\Status;
use App\Models\Task;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'name' => 'naufal',
            'email' => 'naufal@gmail.com',
            'password' => bcrypt('password123')
        ]);

        Status::create([
            'status' => 'to-do'
        ]);

        Status::create([
            'status' => 'on-progress'
        ]);

        Status::create([
            'status' => 'completed'
        ]);


        Task::create([
            "title" => "login system",
            "deadline" => "2023-03-10",
            "description" => "create login system in frontend, api using laravel and front end using react js",
            "level" => 2,
            "status_id" => 2,
            "user_id" => 1
        ]);

        Task::create([
            "title" => "drag and drop system",
            "deadline" => "2023-03-10",
            "description" => "create drag and drop in frontend, api using laravel and front end using react js",
            "level" => 2,
            "status_id" => 2,
            "user_id" => 1
        ]);
    }
}
