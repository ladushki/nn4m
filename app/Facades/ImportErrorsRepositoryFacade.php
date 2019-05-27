<?php


namespace App\Facades;

use App\Repositories\ImportErrorsRepository;

use Illuminate\Support\Facades\Facade;

class ImportErrorsRepositoryFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ImportErrorsRepository::class;
    }

}
