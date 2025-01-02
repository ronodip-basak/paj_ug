<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeviceTest extends TestCase
{
    public function test_device_create(): void {
        $user = User::factory()->create();
        $imei = fake()->imei();
        $this->actingAs($user)->post("/api/devices", [
            'name' => fake()->name(),
            'model' => fake()->name(),
            'imei' => $imei
        ], [
            "Accept" => "application/json"
        ])->assertStatus(201)->assertJsonPath("imei", $imei);

        $this->actingAs($user)->post("/api/devices", [
            'name' => fake()->name(),
            'model' => fake()->name(),
            'imei' => $imei
        ], [
            "Accept" => "application/json"
        ])->assertStatus(422)->assertJsonPath("errors.imei.0", "The imei has already been taken.");
    }

    public function test_device_view_and_unauthorized_view(){
        $first_user = User::factory()->create();
        $second_user = User::factory()->create();
        $imei = fake()->imei();
        $this->actingAs($first_user)->post("/api/devices", [
            'name' => fake()->name(),
            'model' => fake()->name(),
            'imei' => $imei
        ], [
            "Accept" => "application/json"
        ])->assertStatus(201)->assertJsonPath("imei", $imei);

        $this->actingAs($second_user)->get("/api/devices/" . $imei)->assertStatus(403);
        $this->actingAs($first_user)->get("/api/devices/" . $imei)->assertStatus(200);
    }
}
