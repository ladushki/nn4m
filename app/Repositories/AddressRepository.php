<?php

namespace App\Repositories;

use App\Address;
use App\Repositories\AddressRepositoryInterface as AddressRepInterface;
use App\Repositories\RepositoryException;

class AddressRepository implements AddressRepInterface
{

    protected $model;

    public function __construct(Address $model)
    {
        $this->model = $model;
    }


    public function save($item)
    {
        return $this->model->updateOrCreate(['lon' => $item['lon'], 'lat' => $item['lat']], $item);
    }

}
