<?php

namespace Database\Seeders;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function __construct(protected UserRepository $users)
    {
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                "password" => bcrypt("admin"),
                "fullName" => "John Doe",
                "username" => "johndoe",
                "email" => "admin@vuexy.com",
                "role" => "admin",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "password" => bcrypt("client"),
                "fullName" => "Jane Doe",
                "username" => "janedoe",
                "email" => "client@vuexy.com",
                "role" => "client",
                "created_at" => now(),
                "updated_at" => now(),
            ],
        ];

        DB::transaction(function () use ($users) {
            foreach ($users as $user) {
                $this->users->create($user);
            }
        });
    }
}
