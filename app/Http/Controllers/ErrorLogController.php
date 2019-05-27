<?php

namespace App\Http\Controllers;

use App\Repositories\ImportErrorsRepository;
use App\Resources\ErrorLog as LogResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection as ResourceCollection;

class ErrorLogController extends Controller
{

    public $repository;

    public function __construct(ImportErrorsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return ResourceCollection
     */
    public function index(): ResourceCollection
    {

        return LogResource::collection($this->repository->getLatestErrors());
    }

    /**
     * @param $number
     *
     * @return ResourceCollection
     */
    public function show($number): ResourceCollection
    {
        $errors = $this->repository->getLatestLogByStoreNumber($number);

        return LogResource::collection($errors);
    }
}
