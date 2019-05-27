<?php

namespace Tests\Feature;

use App\ImportLog;
use App\ImportLogError;
use Tests\TestCase;

class ErrorLogTest extends TestCase
{

    /**
     * Index
     *
     * @return void
     */
    public function testIndexWithError()
    {
        $response = $this->json('POST', '/api/error', []);

        $response->assertStatus(405);

    }

    /**
     * Index
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->json('GET', '/api/error', []);

        $response->assertStatus(200);
    }

    /**
     * Show
     *
     * @return void
     */
    public function testShow()
    {

        $row = factory(ImportLog::class)->create();
        factory(ImportLogError::class)->create([
            'store_number'  => '0',
            'import_log_id' => $row->id,
            'column_name'   => 'store_number',
            'description'   => 'not 0',
        ]);

        $response = $this->json('GET', '/api/error/0', []);

        $response->assertStatus(200);

        $response->assertJsonFragment(['store_number']);

        $response = $this->json('GET', '/api/error/101211111111', []);

        $response->assertStatus(200)->assertJsonCount(1)->assertJson(['data' => []]);
    }

}
