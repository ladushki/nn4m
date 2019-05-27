<?php


namespace App\Repositories;

use Illuminate\Support\Facades\Facade;

class AddressRepositoryFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return AddressRepository::class;
    }

}
