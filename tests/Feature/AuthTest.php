<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RushApp\Core\Models\Role;
use Tests\BaseFeatureTest;

class AuthTest extends BaseFeatureTest
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function login()
    {
        $role = Role::create(['name' => 'User']);

        $user = User::factory()->create();
        $user->roles()->save($role);

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk()->assertJsonStructure(['token']);
    }

    /**
     * @test
     */
    public function loginWithWrongCredentials()
    {
        $response = $this->postJson('/login', [
            'email' => 'test@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function logout()
    {
        $user = User::factory()->create();
        $this->signIn($user);

        $this->postJson('/logout')->assertOk();
        $this->postJson('/logout')->assertStatus(401);
    }
}
