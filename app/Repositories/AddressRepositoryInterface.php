<?php

namespace App\Repositories;

interface AddressRepositoryInterface
{

    public function create($item);
    
    public function update($item);

    public function exists($item);

    public function find($item);

    public function createGetId($item);

    public function getAddressByCoordinates($lon, $lat);
}
