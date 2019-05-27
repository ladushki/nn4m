<?php

namespace App\Providers;

use App\ImportLog;
use App\ImportLogError;
use Illuminate\Support\ServiceProvider;

class ImportErrorsRepositoryServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind('Repositories\ImportErrorsRepositoryInterface', static function ($app) {
            return new ImportErrorsRepository(
                new ImportLogError(),
                new ImportLog()
            );
        });
    }
}
