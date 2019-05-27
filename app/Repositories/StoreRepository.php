<?php

namespace App\Repositories;

use App\Repositories\RepositoryException;
use App\Repositories\StoreRepositoryInterface as StoreRepInterface;
use App\Store;

class StoreRepository implements StoreRepInterface
{

    protected $model;

    public function __construct(Store $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->with('address')->get();
    }

    public function getStoreByNumber($number)
    {

        $store = $this->model->where('store_number', '=', (int)$number)->first();

        return $store ?? null;
    }

    public function save($item)
    {
        return $this->model->updateOrCreate(['store_number' => $item['store_number']], $item);
    }

    public function create($item)
    {
        return $this->model->create($item);
    }

    public function update($item)
    {
        $this->model->fill($item)->save();

        return $this->model;
    }

    public function exists($item): bool
    {
        $store = $this->getStoreByNumber((int)$item['store_number']);

        return (bool)$store;
    }
}
