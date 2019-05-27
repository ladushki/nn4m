<?php

namespace Tests\Unit;

use App\Address;
use App\Repositories\AddressRepository;
use Tests\TestCase;

class AddressRepoTest extends TestCase
{

    public $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new AddressRepository(new Address());
    }


    public function testNotExists()
    {
        $item = $this->getItem();
      
        $response = $this->repository->exists($item);
        $this->assertFalse($response);
    }

    public function testCreate()
    {
        $item = $this->getItem();

        $response = $this->repository->create($item);

        $this->assertIsObject($response);
        $this->assertEquals($response->address_line_2, 'Debenhams Bedford');
        $this->assertNotEquals($response->address_line_1, 'Debenhams Bedford');
    }

    public function testUpdate()
    {
        $item = $this->getItem();

        $row = factory(Address::class)->create($item);


        $item['address_line_1'] = 'Test2';

        $response = $this->repository->update($item);

        $this->assertIsInt($response);
    }

    public function testFind()
    {
        $item = $this->getItem();

        $row = factory(Address::class)->create($item);

        $response = $this->repository->find($row->id);

        $this->assertIsObject($response);
    }

    public function testGetAddressByCoordinates()
    {
        $item =$this->getItem();
        factory(Address::class)->create($item);

        $response = $this->repository->getAddressByCoordinates('-0.466730',  '52.136900');

        $this->assertIsObject($response);
        $this->assertEquals($response->address_line_2, 'Debenhams Bedford');
        $this->assertNotEquals($response->address_line_1, 'Debenhams Bedford');
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @return array
     */
    protected function getItem(): array
    {
        $item = [
            'address_line_1' => 'Debenhams Retail plc',
            'address_line_2' => 'Debenhams Bedford',
            'address_line_3' => '48-54 High Street',
            'city'           => 'Bedford',
            'county'         => 'Bedfordshire',
            'country'        => 'United Kingdom',
            'lat'            => '52.136900',
            'lon'            => '-0.466730',
        ];

        return $item;
    }
}
