<?php

namespace Tests\Unit;

use App\Repositories\StoreRepository;
use App\Store;
use Tests\TestCase;

class StoreRepoTest extends TestCase
{

    public $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new StoreRepository(new Store);
    }


    public function testNotExists()
    {
        $item = [
            'store_number'       => 123,
            'name'               => 'Test',
            'site_id'            => null,
            'phone_number'       => null,
            'manager'            => null,
            'cfslocation'        => null,
            'delivery_lead_time' => null,
            'cfs_flag'           => false,
            'standardhours'      => null,
            'address_id'         => false,
        ];

        $response = $this->repository->exists($item);
        $this->assertFalse($response);
    }

    public function testCreate()
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

        //$row = factory(Store::class)->create($item);

        $response = $this->repository->create($item);

        $this->assertIsObject($response);
        $this->assertEquals($response->store_number, 1234);
    }

    public function testUpdate()
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

        $row = factory(Store::class)->create($item);

        $store = \Mockery::mock('Eloquent', 'App\Store');

        $item['name'] = 'Test2';

        $response = $this->repository->update($item);

        $this->assertIsObject($response);
        $this->assertEquals('Test2', $response->name);
    }

    public function testGetStoreByNumber()
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

        factory(Store::class)->create($item);

        $response = $this->repository->getStoreByNumber('1234');

        $this->assertIsObject($response);
        $this->assertEquals('Test', $response->name);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
