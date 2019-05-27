<?php

namespace App\Repositories;

use App\ImportLog;
use App\ImportLogError;
use App\Repositories\ImportErrorsRepositoryInterface as ErrorRepInterface;

class ImportErrorsRepository implements ErrorRepInterface
{

    protected $model;
    protected $parentModel;

    public function __construct(ImportLog $parentModel, ImportLogError $model)
    {
        $this->model       = $model;
        $this->parentModel = $parentModel;
    }

    public function getLatestErrors()
    {
        return $this->model->with('log')->last()->get();
    }

    public function getLatestLog()
    {
        return $this->model->with('log')->last()->first();
    }

    public function findLog()
    {
        return $this->parentModel;
    }

    public function getLatestLogByStoreNumber($number)
    {
        $store = $this->model->with('log')->last()->where('store_number', '=', (int)$number)->get();

        return $store ?? null;
    }

    public function saveLog($status)
    {
        return $this->parentModel->fill($status)->save();
    }

    public function saveErrors($errors)
    {
        collect($errors)->each(function ($errors, $storeNumber) {
            return collect($errors)->each(function ($error, $name) use ($storeNumber) {
                $errorLog = $this->model->firstOrCreate([
                    'store_number'  => $storeNumber,
                    'column_name'   => $name,
                    'description'   => implode(';', (array)$error),
                ]);

                $this->parentModel->errors()->save($errorLog);
            });
        });
    }
}
