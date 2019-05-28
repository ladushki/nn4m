<?php


namespace App\Providers;


use App\Services\StoreImportService;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class StoreImportServiceServiceProvider extends ServiceProvider
{

    /**
     * Registers the service in the IoC Container
     *
     */
    public function register()
    {
        $this->app->bind('storeImportService', static function ($app) {
            return new StoreImportService(
                new Request()
            );
        });
    }
    public function provides()
    {
        return [
            'App\Services\StoreImportService',
        ];
    }
}
