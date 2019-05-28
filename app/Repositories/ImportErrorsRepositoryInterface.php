<?php

namespace App\Repositories;

interface ImportErrorsRepositoryInterface
{

    public function getLatestLogByStoreNumber($number);

    public function getLatestErrors();

    public function getLatestLog();

    public function findLog();

    public function saveLog($data);

    public function saveErrors($data);

    public function getLogById($id);

}
