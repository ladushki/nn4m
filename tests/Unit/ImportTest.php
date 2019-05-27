<?php

namespace Tests\Unit;

use App\Address;
use App\Repositories\AddressRepository;
use App\Repositories\StoreRepository;
use App\Services\StoreImportService;
use App\Store;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class ImportTest extends TestCase
{

    public $import;
    public $storeImport;


    public function setUp(): void
    {
        parent::setUp();
        $this->storeImport = Mockery::mock('App\Services\StoreImportService');

        $this->import = new StoreImportService(
            \Mockery::mock(Request::class),
            \Mockery::mock(StoreRepository::class),
            \Mockery::mock(AddressRepository::class)
        );
    }

    public function testTets()
    {
        $storeImport = new StoreImportService(new Request(),
            new StoreRepository(new Store()), new AddressRepository(new Address()));
        $storeImport->resolveXmlObject($this->xml());
        $storeImport->setFilename('test.xml');

        $content = $storeImport->getContent();

        $this->expectException(\ErrorException::class);
        $storeImport->importFromArray(current($content));
    }


    public function testParseXml()
    {

        $response = xmlToArray('<xml><stores><store><name>Test</name></store></stores></xml>');

        $this->assertTrue(is_array($response));

        $this->assertEquals(['stores' => ['store' => ['name' => 'Test']]], $response);
    }

    public function testStoreMap()
    {

        $response = $this->import->map(['name' => 'Test', 'number' => '123']);

        $this->assertIsArray($response);

        $this->assertArrayHasKey('store_number', $response);
        $this->assertArrayNotHasKey('number', $response);

        $this->assertEquals([
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
        ], $response);
    }

    public function testStoreValidate()
    {
        $item     = [
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

        $response = $this->import->importFromArray([$item]);

        $this->assertIsArray($response);

        $this->assertNotEmpty($this->import->status['errors']);

        $this->assertEquals($this->import->status['inserted'], 0);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function xml()
    {
        return '<xml>
<stores><store>
<number><![CDATA[103]]></number>
<code><![CDATA[BED]]></code>
<name><![CDATA[Bedford]]></name>
<manager><![CDATA[Kerry Tearle]]></manager>
<address>
<address_line_1>Debenhams Retail plc</address_line_1>
<address_line_2><![CDATA[Debenhams Bedford]]></address_line_2>
<address_line_3><![CDATA[48-54 High Street]]></address_line_3>
<city><lat><![CDATA[52.136900]]></lat>
<lon><![CDATA[-0.466730]]></lon></city>
<county><![CDATA[Bedfordshire]]></county>
<country><![CDATA[United Kingdom]]></country>
<postcode><![CDATA[MK40 1SP]]></postcode>
</address>
<siteid><![CDATA[GB]]></siteid>
<coordinates>
<lat><![CDATA[52.136900]]></lat>
<lon><![CDATA[-0.466730]]></lon>
</coordinates>
<phone_number><![CDATA[0344 800 8877]]></phone_number>
<cfslocation><![CDATA[Second floor]]></cfslocation>
<delivery_lead_time><![CDATA[2]]></delivery_lead_time>
<cfs_flag><![CDATA[Y]]></cfs_flag>
<standardhours>
<monday><![CDATA[09:30 - 17:30]]></monday>
<tuesday><![CDATA[09:30 - 17:30]]></tuesday>
<wednesday><![CDATA[09:30 - 17:30]]></wednesday>
<thursday><![CDATA[09:30 - 17:30]]></thursday>
<friday><![CDATA[09:30 - 17:30]]></friday>
<saturday><![CDATA[09:30 - 17:30]]></saturday>
<sunday><![CDATA[10:00 - 16:00]]></sunday>
</standardhours></store></stores></xml>';
    }
}
