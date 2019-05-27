<?php


namespace App\Facades;

use App\Repositories\StoreRepository;

use Illuminate\Support\Facades\Facade;

class StoreRepositoryFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return StoreRepository::class;
    }

}
