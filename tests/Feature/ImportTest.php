<?php

namespace Tests\Feature;

use App\Address;
use App\Exceptions\InvalidContentException;
use App\ImportLog;
use App\Repositories\AddressRepository;
use App\Repositories\StoreRepository;
use App\Services\AddressImportService;
use App\Services\StoreImportService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportTest extends TestCase
{

    public function testIndex()
    {
        $response = $this->json('GET', '/', []);

        $response->assertStatus(200);
    }

    public function testResultsWithError()
    {
        $response = $this->json('GET', '/results/', []);

        $response->assertStatus(404);
    }

    public function testShowResults()
    {
        $log = factory(ImportLog::class)->create([
            'filename' => 'test.xml',
        ]);

        $response = $this->json('GET', '/results/' . $log->id, []);

        $response->assertStatus(200)->assertViewIs('import.result');
    }


    public function testUploadWithError()
    {
        $response = $this->json('POST', '/upload', []);

        $response->assertStatus(302);

    }

    public function testStoreFailedImport()
    {
        $data     = '<xml><test></test></xml>';
        $response = $this->setImportData($data);
        $content  = $response->getContent();

        $this->assertIsArray($content);
        $this->assertArrayHasKey('test', $content);

        $result = $response->import();

        $this->assertIsArray($result);
        $this->assertFalse($result['is_completed']);
    }

    public function testStoreImport()
    {
        $data              = $this->xml();
        $request           = \Mockery::mock(Request::class);
        $storeRepository   = \Mockery::mock(StoreRepository::class);
        $addressRepository = \Mockery::mock(AddressRepository::class);

        $import        = new StoreImportService($request, $storeRepository, $addressRepository);
        $addressImport = new AddressImportService($request, $addressRepository);

        $response = $import->resolveXmlObject($data);

        $response->setFilename('test.xml');

        $content = $response->getContent();

        $row = [
            "address_line_1" => "Debenhams Retail plc",
            "address_line_2" => "Debenhams Bedford",
            "address_line_3" => "48-54 High Street",
            "city"           => "Bedford",
            "county"         => "Bedfordshire",
            "country"        => "United Kingdom",
            "lat"            => "52.136900",
            "lon"            => "-0.466730",
        ];
        $addressImport->addressRepository->shouldReceive('getAddressByCoordinates')
                                         ->with('-0.466730', '52.136900')
                                         ->once()
                                         ->andReturn(Address::class);

        $mapped = $addressImport->map($content['stores']['store']);
        $addressImport->addressRepository->shouldReceive('getAddressByCoordinates')
                                         ->with('-0.466730', '52.136900')
                                         ->once()
                                         ->andReturn(Address::class);
        $exists  = $addressImport->exists($mapped);
        $address = \Mockery::mock('Eloquent', 'App\Address');

        if ($exists) {
            $addressImport->addressRepository->shouldReceive('update')->with($mapped)->andReturn($address);
        } else {
            $addressImport->addressRepository->shouldReceive('create')->with($mapped)->andReturn($address);
        }


        $storeMapped = $import->map(array_filter($content['stores']['store']), 0);

        $import->storeRepository->shouldReceive('getStoreByNumber')->once()->with('103');

        $store = \Mockery::mock('Eloquent', 'App\Store');

        $storeExists = $import->exists($storeMapped);
        if ($storeExists) {
            $import->storeRepository->shouldReceive('update')->with($storeMapped)->andReturn($store);
        } else {
            $import->storeRepository->shouldReceive('create')->with($storeMapped)->andReturn($store);
        }

        // $import->storeRepository->shouldReceive('exists')->once()->with($content['stores']['store']);

        //  $response = $addressImport->run($content['stores']['store']);
    }

    public function testSetXml()
    {
        $request           = \Mockery::mock(Request::class);
        $storeRepository   = \Mockery::mock(StoreRepository::class);
        $addressRepository = \Mockery::mock(AddressRepository::class);
        $import = new StoreImportService($request, $storeRepository, $addressRepository);

        $this->expectException(InvalidContentException::class);
        $response = $import->resolveXmlObject('');

        $this->assertEquals([], $response->getContent());
    }

    public function testAddressImport()
    {
        $data              = $this->xml();
        $request           = \Mockery::mock(Request::class);
        $storeRepository   = \Mockery::mock(StoreRepository::class);
        $addressRepository = \Mockery::mock(AddressRepository::class);

        $import        = new StoreImportService($request, $storeRepository, $addressRepository);
        $addressImport = new AddressImportService($request, $addressRepository);

        $response = $import->resolveXmlObject($data);

        $response->setFilename('test.xml');

        $content = $response->getContent();

        $row = [
            "address_line_1" => "Debenhams Retail plc",
            "address_line_2" => "Debenhams Bedford",
            "address_line_3" => "48-54 High Street",
            "city"           => "Bedford",
            "county"         => "Bedfordshire",
            "country"        => "United Kingdom",
            "lat"            => "52.136900",
            "lon"            => "-0.466730",
        ];

        $mapped = $addressImport->map($content['stores']['store']);
        $addressImport->addressRepository->shouldReceive('getAddressByCoordinates')
                                         ->with('-0.466730', '52.136900')
                                         ->once()
                                         ->andReturn(Address::class);
        $exists  = $addressImport->exists($mapped);
        $address = \Mockery::mock('Eloquent', 'App\Address');

        if ($exists) {
            $addressImport->addressRepository->shouldReceive('update')->with($mapped)->andReturn($address);
        } else {
            $addressImport->addressRepository->shouldReceive('create')->with($mapped)->andReturn($address);
        }
    }

    public function testUpload()
    {
        Storage::fake('uploads');

        $response = $this->json('POST', '/upload', [
            'xml' => UploadedFile::fake()->create('stores.pdf', 2000),
        ]);

        $response->assertSessionHasErrors(['xml']);

        $response = $this->json('POST', '/upload', [
            'xml' => null,
        ]);

        $response->assertSessionHasErrors(['xml']);

        $response = $this->json('POST', '/upload', [
            'xml' => UploadedFile::fake()->create('stores.xml', 2000),
        ]);

        $response->assertStatus(302);
    }

    public function testFileLoad()
    {
        $request           = \Mockery::mock(Request::class);
        $storeRepository   = \Mockery::mock(StoreRepository::class);
        $addressRepository = \Mockery::mock(AddressRepository::class);

        $this->expectException(InvalidContentException::class);

        $import = new StoreImportService($request, $storeRepository, $addressRepository);
        $response = $import->load('test.txt');
        $response->assertStatus(500);
    }

    public function testLog()
    {
        $newLog = factory(ImportLog::class)->create([
            'filename' => 'test.xml',
        ]);

        $this->assertEquals($newLog->filename, 'test.xml');
        $log = ImportLog::latest()->first();

        $this->assertEquals($log->id, $newLog->id);
    }


    protected function setImportData($data)
    {
        $request           = \Mockery::mock(Request::class);
        $storeRepository   = \Mockery::mock(StoreRepository::class);
        $addressRepository = \Mockery::mock(AddressRepository::class);

        $import = \Mockery::mock(new StoreImportService($request, $storeRepository, $addressRepository));

        $response = $import->resolveXmlObject($data);

        $response->setFilename('test.xml');

        return $response;
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
<city><![CDATA[Bedford]]></city>
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
