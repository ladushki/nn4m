<?php

namespace App\Providers;

use App\ImportLog;
use App\ImportLogError;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ImportErrorsRepository;

class ImportErrorsRepositoryServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind('Repositories\ImportErrorsRepositoryInterface', static function ($app) {
            return new ImportErrorsRepository(
                new ImportLog(),
                new ImportLogError()
            );
        });
    }
}
