<?php

namespace Tests\Unit;

use App\ImportLog;
use App\ImportLogError;
use App\Repositories\ImportErrorsRepository;
use ImportErrorLogger;
use Tests\TestCase;

class ImportLogRepoTest extends TestCase
{

    public $repository;

    public function testGetModel()
    {
        $item = [
            'filename' => 'test.xml',
        ];

        ImportErrorLogger::saveLog($item);
        ImportErrorLogger::findLog();

        $response = ImportErrorLogger::getModel();

        $this->assertIsObject($response);
        $this->assertEquals($response, new ImportLogError());
    }

    public function testServiceProvider()
    {
        $this->assertTrue($this->app->bound('Repositories\ImportErrorsRepositoryInterface'));

        $this->assertInstanceOf(ImportErrorsRepository::class, $this->app->get('Repositories\ImportErrorsRepositoryInterface'));
    }

    public function testLogCreate()
    {
        $item = [
            'filename' => 'test.xml',
        ];

        $response = ImportErrorLogger::saveLog($item);
        $log      = ImportErrorLogger::findLog();

        $this->assertTrue($response);
        $this->assertEquals($log->filename, 'test.xml');
    }

    public function testSaveErrors()
    {
        $item = [
            'filename' => 'test.xml',
        ];
        ImportErrorLogger::saveLog($item);

        $item = [
            'store_number' => '0',
            'column_name'  => 'store_number',
            'description'  => 'Error string',
        ];

        $response = ImportErrorLogger::saveErrors([$item]);

        $this->assertIsArray($response->toArray());
        $this->assertEquals($response->toArray()[0]['column_name'], 'store_number');
    }

    public function testFindLog()
    {
        $item = [
            'filename' => 'test.xml',
        ];
        ImportErrorLogger::saveLog($item);
        $response = ImportErrorLogger::findLog();

        $this->assertEquals($response->filename, 'test.xml');
    }
    public function testGetLogById()
    {
        $item = [
            'filename' => 'test.xml',
        ];
        $log = factory(ImportLog::class)->create();
        $response = ImportErrorLogger::getLogById($log->id);

        $this->assertEquals($response->filename, 'test.xml');
    }

    public function testLatestLogByStoreNumber()
    {
        $item = [
            'filename' => 'test.xml',
        ];
        ImportErrorLogger::saveLog($item);

        $item = [
            'store_number' => '0',
            'column_name'  => 'store_number',
            'description'  => 'Error string',
        ];
        ImportErrorLogger::saveErrors([$item]);

        $log = ImportErrorLogger::getLatestLogByStoreNumber('0');

        $this->assertEquals($log[0]->column_name, 'store_number');
    }

    public function testLatestErrors()
    {
        $item = [
            'filename' => 'test.xml',
        ];
        ImportErrorLogger::saveLog($item);

        $item = [
            'store_number' => '0',
            'column_name'  => 'store_number',
            'description'  => 'Error string',
        ];
        ImportErrorLogger::saveErrors([$item]);

        $log = ImportErrorLogger::getLatestLog();

        $this->assertEquals($log->column_name, 'store_number');
    }
}
