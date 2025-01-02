<?php

namespace Tests\Feature;


use Tests\TestCase;

use function Illuminate\Log\log;

class AuthTest extends TestCase
{
    public function test_registration_and_login(): void
    {
        $email = fake()->email();
        $password = fake()->password() . "33fF#";
        $regRes = $this->post("/api/auth/register", [
            'name' => fake()->name(),
            'email' => $email,
            'password' => $password
        ], [
            "Accept" => "application/json"
        ]);
        $regRes->assertStatus(201);

        $invalidLoginResponse = $this->post("/api/auth/login", [
            "email" => $email,
            "password" => fake()->password() . "jhfdghdguaghejb"
        ], [
            "Accept" => "application/json"
        ]);
        $invalidLoginResponse->assertStatus(422);

        $loginRes = $this->post("/api/auth/login", [
            "email" => $email,
            "password" => $password
        ], [
            "Accept" => "application/json"
        ]);

        $loginRes->assertStatus(200)->assertJsonStructure([
            "data" => [
                "access_token",
                "refresh_token"
            ]
        ]);
    }
    public function test_invalid_log_in()
    {
        $response = $this->post("/api/auth/register", [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => fake()->password(6, 6)
        ], [
            'Accept' => "application/json"
        ]);
        $response->assertStatus(422);
    }

    
}
