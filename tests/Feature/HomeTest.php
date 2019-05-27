<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class HomeTest extends TestCase
{

    protected function successfulLoginRoute()
    {
        return route('home');
    }

    protected function loginGetRoute()
    {
        return route('login');
    }


    /**
     * Index
     *
     * @return void
     */
    public function testIndexRedirect()
    {

        $response = $this->get('/home');

        $response->assertRedirect($this->loginGetRoute());
    }

    /**
     * Index
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->json('GET', '/home', []);

        $response->assertStatus(401);

    }

    public function testIndexLogged()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get('/home');

        $response->assertViewIs('home');

        $response->assertSeeText('You are logged in!');
    }

}
