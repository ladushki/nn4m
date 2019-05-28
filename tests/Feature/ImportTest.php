<?php

namespace Tests\Feature;

use App\Exceptions\InvalidContentException;
use App\ImportLog;
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
        $data    = '<xml><test></test></xml>';
        $request = \Mockery::mock(Request::class);
        $import  = \Mockery::mock(new StoreImportService($request));

        $response = $import->resolveXmlObject($data);
        $response->setFilename('test.xml');
        $content = $response->getContent();

        $this->assertIsArray($content);
        $this->assertArrayHasKey('test', $content);

        $result = $response->run();

        $this->assertFalse($result);
        $this->assertEquals($response->status['is_completed'], false);
    }

    public function testInsert()
    {
        $data    = $this->xml();
        $request = \Mockery::mock(Request::class);
        $import  = \Mockery::mock(new StoreImportService($request));

        $response = $import->resolveXmlObject($data);
        $response->setFilename('test.xml');
        $content = $response->getContent();

        $this->assertIsArray($content);
        $this->assertArrayHasKey('stores', $content);

        $result = $response->run();

        $this->assertIsObject($result);
        $this->assertEquals($response->status['is_completed'], true);
        $this->assertEquals($response->status['inserted'], 1);
        $this->assertEquals($response->status['updated'], 0);
    }

    public function testUpdate()
    {
        $data    = $this->xml();
        $request = \Mockery::mock(Request::class);
        $import  = \Mockery::mock(new StoreImportService($request));

        $response = $import->resolveXmlObject($data);
        $response->setFilename('test.xml');
        $content = $response->getContent();

        $this->assertIsArray($content);
        $this->assertArrayHasKey('stores', $content);

        $result = $response->run();

        $this->assertIsObject($result);
        $this->assertEquals($response->status['is_completed'], true);
        $this->assertEquals($response->status['inserted'], 1);
        $this->assertEquals($response->status['updated'], 0);


        $response2 = $import->resolveXmlObject($data);
        $response2->run();
        $this->assertEquals($response->status['updated'], 1);
    }


    public function testSetXml()
    {
        $request = \Mockery::mock(Request::class);
        $import  = new StoreImportService($request);

        $this->expectException(InvalidContentException::class);
        $response = $import->resolveXmlObject('');

        $this->assertEquals([], $response->getContent());
    }

    public function testUploadValidation()
    {
        Storage::fake('uploads');

        $response = $this->json('POST', '/upload', [
            'xml' => UploadedFile::fake()->create('stores.pdf', 200),
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
        $request = \Mockery::mock(Request::class);

        $this->expectException(InvalidContentException::class);

        $import   = new StoreImportService($request);
        $response = $import->load('test.txt');
        $response->assertStatus(500);
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
