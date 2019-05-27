<?php


namespace App\Providers;

use App\Address;
use Illuminate\Support\ServiceProvider;

class AddressRepositoryServiceProvider extends ServiceProvider
{

    public function register(): void
    {

        $this->app->bind('Repositories\AddressRepositoryInterface', static function ($app) {
            return new AddressRepository(new Address());
        });
    }
}
