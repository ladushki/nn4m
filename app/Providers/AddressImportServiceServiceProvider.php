<?php


namespace App\Providers;


use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AddressImportServiceServiceProvider extends ServiceProvider
{

    /**
     * Registers the service in the IoC Container
     *
     */
    public function register()
    {
        $this->app->bind('addressImportService', static function ($app) {
            return new StoreImportService(new Request(), $app->make('Repositories\AddressRepository'));
        });
    }
}
