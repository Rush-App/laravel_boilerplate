<?php

namespace Tests\Feature\Users\UserPersonalData;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseFeatureTest;

class UserPersonalDataTest extends BaseFeatureTest
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function getUserPersonalData()
    {
        $this->signIn();

        $response = $this->getJson('/get-personal-data');
        $response->assertStatus(200)->assertJsonStructure([
            'id',
            'name',
            'email',
            'usage_policy',
            'created_at',
            'updated_at',
        ]);
    }

    /**
     * @test
     */
    public function updateUserPersonalData()
    {
        $user = User::factory()->create();
        $this->signIn($user);

        $data = [
            'name' => 'Alex',
            'email' => $user->email,
        ];

        $response = $this->postJson('/update-personal-data/', $data);

        $response->assertStatus(200)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function changePassword()
    {
        $this->signIn();

        $data = [
            'old_password' => 'password',
            'password' => '88888888',
            'password_confirmation' => '88888888',
        ];

        $response = $this->postJson('/change-password', $data);

        $response->assertStatus(200)->assertJsonStructure([
            'token',
        ]);
    }
}
