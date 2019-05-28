<?php


namespace App\Providers;


use App\Store;
use Illuminate\Support\ServiceProvider;
use App\Repositories\StoreRepository;

class StoreRepositoryServiceProvider extends ServiceProvider
{

    public function register(): void
    {

        $this->app->bind('Repositories\StoreRepositoryInterface', static function ($app) {
            return new StoreRepository(new Store());
        });
    }
}
