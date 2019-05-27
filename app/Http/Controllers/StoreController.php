<?php

namespace App\Http\Controllers;

use App\Repositories\StoreRepository;
use App\Resources\Store as StoreResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection as ResourceCollection;

class StoreController extends Controller
{

    public $repository;

    public function __construct(StoreRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $result = $this->repository->getAll();

        return StoreResource::collection($result);
    }

    /**
     * @param $number
     *
     * @return StoreResource
     */
    public function show($number): StoreResource
    {
        $store = $this->repository->getStoreByNumber($number);

        if (!$store) {
            abort(404);
        }
        return new StoreResource($store);
    }

}
