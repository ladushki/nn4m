<?php

namespace App\Interactions;

use StoreBuilder;
use ZachFlower\EloquentInteractions\Interaction;

class CreateStore extends Interaction
{

    public $validations = [
        'name'         => 'required|max:255',
        'address_id'   => 'required',
        'store_number' => 'required|integer',
        'site_id'      => 'required',
        'phone_number' => 'required',
        'cfs_flag'     => 'required|integer',
    ];

    public function execute()
    {
        return StoreBuilder::save($this->params);
    }
}
