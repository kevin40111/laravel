<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Repositories\UserRepository;
use App\Models\User;

class UserRepositoriesTest extends TestCase
{
    use DatabaseMigrations;

    protected $enablesPackageDiscoveries = true;

    public function testCreateUser(): void
    {
        $userData = [
            "fullName" => fake()->name(),
            "username" => fake()->name(),
            "role" => "client",
            "email" => fake()->email(),
            "password" => bcrypt(fake()->password()),
        ];

        $userRepository = new UserRepository();
        $userRepository->create($userData);

        $this->assertCount(1, User::whereEmail($userData["email"])->get());
    }

    public function testFindUser(): void
    {
        $userData = [
            "fullName" => fake()->name(),
            "username" => fake()->name(),
            "role" => "client",
            "email" => fake()->email(),
            "password" => bcrypt(fake()->password()),
        ];

        $userRepository = new UserRepository();
        $userRepository->create($userData);

        $this->assertNotEmpty($userRepository->findByEmail($userData["email"]));
        $this->assertEmpty($userRepository->findByEmail(fake()->email()));
    }
}
