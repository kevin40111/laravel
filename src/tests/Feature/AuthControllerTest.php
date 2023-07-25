<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $enablesPackageDiscoveries = true;

    public function testUserLoginVerify(): void
    {
        $password = "123456789";
        $user = User::factory()->create(["password" => bcrypt($password)]);

        /* login verify pass */
        $response = $this->post("auth/login", [
            "email" => $user->email,
            "password" => $password,
        ]);

        $response->assertOk();

        /* login verify fail */
        $response = $this->post("auth/login", [
            "email" => $user->email,
            "password" => "xxxxxxxxx",
        ]);

        $response->assertStatus(401);
        $this->assertEquals("Password incorrect", $response->json()["message"]);
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
    }

    protected function defineRoutes($router)
    {
        require "src/routes/api.php";
    }
}
