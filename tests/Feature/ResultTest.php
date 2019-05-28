<?php

namespace Tests\Feature;

use App\ImportLog;
use App\ImportLogError;
use Tests\TestCase;

class ResultTest extends TestCase
{

    /**
     * Index
     *
     * @return void
     */
    public function testIndexWithError()
    {
        $response = $this->json('GET', '/results', []);

        $response->assertStatus(404);
    }

    /**
     * Index
     *
     * @return void
     */
    public function testIndex()
    {
        $log = factory(ImportLog::class)->create();
        $response = $this->json('GET', '/results/'.$log->id, []);

        $response->assertStatus(200);
        $response = $this->json('GET', '/results/1', []);

        $response->assertStatus(200);

    }
}
