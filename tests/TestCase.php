<?php

namespace Tests;

use App\Exceptions\Handler;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    use CreatesApplication, DatabaseMigrations, DatabaseTransactions;

    private $isExceptionHandlingDisabled = false;

    protected $faker;

    /**
     * Set up the test
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Reset the migrations
     */
    public function tearDown(): void
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
        if ($this->isExceptionHandlingDisabled) {
            $this->markTestIncomplete('Exception handling is disabled.');
        }
    }

    protected function disableExceptionHandling()
    {
        $this->isExceptionHandlingDisabled = true;

        $this->app->instance(ExceptionHandler::class, new class extends Handler
        {

            public function __construct()
            {
            }

            public function report(Exception $e)
            {
            }

            public function render($request, Exception $e)
            {
                throw $e;
            }
        });
    }
}
