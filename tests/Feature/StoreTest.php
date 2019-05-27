<?php

namespace Tests\Feature;

use App\Repositories\StoreRepository;
use App\Store;
use Tests\TestCase;

class StoreTest extends TestCase
{

    /**
     * Index
     *
     * @return void
     */
    public function testIndexWithError()
    {
        $response = $this->json('GET', '/api/store', []);

        $response->assertStatus(200);
    }

    /**
     * Index
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->json('GET', '/api/store', []);

        $response->assertStatus(200);

    }

    public function testShowWithError()
    {
        $response = $this->json('GET', '/api/store/{number}', []);

        $response->assertStatus(404);

    }

    public function testShow()
    {

        $item = [
            'store_number'       => 1234,
            'name'               => 'Test',
            'site_id'            => 1,
            'phone_number'       => '123456',
            'manager'            => null,
            'cfslocation'        => null,
            'delivery_lead_time' => null,
            'cfs_flag'           => false,
            'standardhours'      => '{[]}',
            'address_id'         => false,
        ];

        $repository = new StoreRepository(new Store);

        if (!$repository->exists($item)) {
            factory(Store::class)->create($item);
        }
        $response = $this->json('GET', '/api/store/1234', []);

        $response->assertStatus(200);
    }

}
