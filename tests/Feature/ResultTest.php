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
        $response = $this->get('/results');

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
        $response = $this->get('/results/'.$log->id);

        $response->assertStatus(200);
    }
}
