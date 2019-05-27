<?php

namespace App\Repositories;

interface ImportErrorsRepositoryInterface
{

    public function getLatestLogByStoreNumber($number);

    public function getLatestLogs();

    public function createLogEntry($importStatus);
}
