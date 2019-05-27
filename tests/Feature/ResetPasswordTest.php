<?php

namespace Tests\Feature;

use Tests\TestCase;

class ResetPasswordTest extends TestCase
{

    /**
     * ShowResetForm
     *
     * @return void
     */
    public function testShowResetForm()
    {
        $response = $this->json('GET', '/password/reset/{token}', []);

        $response->assertStatus(200);

    }

    /**
     * Reset
     *
     * @return void
     */
    public function testResetWithError()
    {
        $response = $this->json('POST', '/password/reset', []);

        $response->assertStatus(422);

    }

}
