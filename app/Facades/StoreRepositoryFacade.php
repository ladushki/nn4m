<?php


namespace App\Facades;

use App\Repositories\AddressRepository;
use Illuminate\Support\Facades\Facade;

class AddressRepositoryFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return AddressRepository::class;
    }

}
