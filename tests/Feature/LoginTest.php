<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{

    protected function successfulLoginRoute()
    {
        return route('home');
    }

    protected function loginGetRoute()
    {
        return route('login');
    }

    protected function loginPostRoute()
    {
        return route('login');
    }

    protected function logoutRoute()
    {
        return route('logout');
    }

    protected function successfulLogoutRoute()
    {
        return '/';
    }

    protected function guestMiddlewareRoute()
    {
        return route('home');
    }

    /**
     * ShowLoginForm
     *
     * @return void
     */
    public function testShowLoginFormWithError()
    {
        $response = $this->json('POST', '/login', []);

        $response->assertStatus(422);

    }

    /**
     * ShowLoginForm
     *
     * @return void
     */
    public function testShowLoginForm()
    {
        $response = $this->json('GET', '/login', []);

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');

    }

    /**
     * Login
     *
     * @return void
     */

    public function testLoginWithError()
    {
        $response = $this->json('POST', '/login', ['username' => 1]);

        $response->assertStatus(422);

    }

    /**
     * Login
     *
     * @return void
     */
    public function testLogin()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make($password = 'i-love-laravel'),
        ]);

        $response = $this->post($this->loginPostRoute(), [
            'email'    => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect($this->successfulLoginRoute());
        $this->assertAuthenticatedAs($user);

    }

    /**
     * Logout
     *
     * @return void
     */
    public function testLogoutWithError()
    {
        $response = $this->json('GET', '/logout', []);

        $response->assertStatus(405);

    }

    /**
     * Logout
     *
     * @return void
     */
    public function testLogout()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertStatus(302);

        $response->assertRedirect('/');
    }
}
