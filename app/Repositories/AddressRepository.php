<?php

namespace App\Repositories;

use App\Address;
use App\Repositories\RepositoryException;
use App\Repositories\AddressRepositoryInterface as AddressRepInterface;

class AddressRepository implements AddressRepInterface
{

    protected $model;

    public function __construct(Address $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return  $this->model->where([
            'id' => $id,
        ])->first();
    }

    public function getAddressByCoordinates($lon, $lat)
    {
        $model = $this->model->where([
            'lon' => $lon,
            'lat' => $lat,
        ])->first();

        return $model;
    }


    public function create($item)
    {
        return $this->model->create($item);
    }

    public function createGetId($item)
    {
        $item['created_at'] = date('Y-m-d H:i:s');

        return $this->model->insertGetId($item);
    }

    public function update($item)
    {
        $this->model->fill($item)->save();
        return $this->model->id;
    }

    public function exists($item): bool
    {
        $address = $this->getAddressByCoordinates($item['lon'], $item['lat']);

        return (bool)$address;
    }
}
