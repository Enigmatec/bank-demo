<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_login()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $this->json('POST', route('auth.login'), [
            'email' => $user->email,
            'password' => 'password'
        ])
        ->assertStatus(200);

    }
}
