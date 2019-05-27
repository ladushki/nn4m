<?php

namespace App\Repositories;

use App\ImportLog;
use App\ImportLogError;
use App\Repositories\ImportErrorsRepositoryInterface as ErrorRepInterface;

class ImportErrorsRepository implements ErrorRepInterface
{

    protected $model;

    public function __construct(ImportLogError $model)
    {
        $this->model = $model;
    }

    public function getLatestLogs()
    {
        return $this->model->with('log')->last()->get();
    }

    public function getLatestLogByStoreNumber($number)
    {

        $store = $this->model->with('log')->last()->where('store_number', '=', (int)$number)->get();

        return $store ?? null;
    }

    public function createLogEntry($status)
    {
        $log = new ImportLog();

        $log->fill($status);
        $log->save();

        collect($status['errors'])->each(function ($errors, $storeNumber) use ($log) {
            return collect($errors)->each(function ($error, $name) use ($storeNumber, $log) {
                $errorLog = new ImportLogError;

                $errorLog->store_number  = $storeNumber;
                $errorLog->import_log_id = $log->id;
                $errorLog->column_name   = $name;
                $errorLog->description   = implode(';', (array)$error);

                $errorLog->save();
            });
        });

        return $log;
    }

}
