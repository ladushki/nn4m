<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{

    use RefreshDatabase;

    /**
     * ShowRegistrationForm
     *
     * @return void
     */
    public function testShowRegistrationForm()
    {
        $response = $this->json('GET', '/register', []);

        $response->assertStatus(200);
    }

    /**
     * Register
     *
     * @return void
     */
    public function testRegisterWithError()
    {
        $response = $this->json('POST', '/register', []);

        $response->assertStatus(422);
    }

    public function testUserCanViewARegistrationForm()
    {
        $response = $this->get('/register');

        $response->assertSuccessful();
        $response->assertViewIs('auth.register');
    }

    public function testUserCannotViewARegistrationFormWhenAuthenticated()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get('/register');

        $response->assertRedirect('/home');
    }

    public function testUserCanRegister()
    {
        Event::fake();

        $response = $this->post('register', [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);

        $response->assertRedirect('/home');
        $this->assertCount(1, $users = User::all());
        $this->assertAuthenticatedAs($user = $users->first());
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue(Hash::check('i-love-laravel', $user->password));
        Event::assertDispatched(Registered::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }
}
