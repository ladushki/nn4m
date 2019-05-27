<?php

namespace App\Repositories;

interface StoreRepositoryInterface
{

    public function getStoreByNumber($number);

    public function getAll();

    public function create($item);

    public function update($item);

    public function exists($item);
}
