<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                "role" => "admin",
                "password" => bcrypt("admin"),
                "fullName" => "John Doe111",
                "username" => "johndoe",
                "email" => "admin@vuexy.com",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "role" => "client",
                "password" => bcrypt("client"),
                "fullName" => "Jane Doe",
                "username" => "janedoe",
                "email" => "client@vuexy.com",
                "created_at" => now(),
                "updated_at" => now(),
            ]
        ];
        User::insert($users);
    }
}
