<?php

namespace Tests\Unit;

use AddressBuilder;
use App\Repositories\AddressRepository;
use Tests\TestCase;

class AddressRepoTest extends TestCase
{

    public $repository;

    public function testSave()
    {
        $item = $this->getItem();

        $response = AddressBuilder::save($item);

        $this->assertIsObject($response);
        $this->assertEquals($response->address_line_2, 'Debenhams Bedford');
        $this->assertNotEquals($response->address_line_1, 'Debenhams Bedford');
    }

    public function testServiceProvider()
    {
        $this->assertTrue($this->app->bound('Repositories\AddressRepositoryInterface'));
        $this->assertInstanceOf(AddressRepository::class, $this->app->get('Repositories\AddressRepositoryInterface'));
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
