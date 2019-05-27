<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{

    /**
     * ShowLinkRequestForm
     *
     * @return void
     */
    public function testShowLinkRequestForm()
    {
        $response = $this->json('GET', '/password/reset', []);

        $response->assertStatus(200);
    }

    /**
     * SendResetLinkEmail
     *
     * @return void
     */
    public function testSendResetLinkEmailWithError()
    {
        $response = $this->json('POST', '/password/email', []);

        $response->assertStatus(422);
    }

    /**
     * SendResetLinkEmail
     *
     * @return void
     */
    public function testSendResetLinkEmail()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make($password = 'i-love-laravel'),
            'email'    => 'larissab@siol.net',
        ]);

        $response = $this->json('POST', '/password/email', ['email' => 'larissab@siol.net']);

        $response->assertStatus(302);
    }

}
