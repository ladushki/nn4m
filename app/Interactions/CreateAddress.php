<?php

namespace App\Interactions;

use AddressBuilder;
use ZachFlower\EloquentInteractions\Interaction;

class CreateAddress extends Interaction
{

    /**
     * Parameter validations
     *
     * @var array
     */
    public $validations = [
        'address_line_1' => 'required',
        'lat'            => 'required',
        'lon'            => 'required',
    ];

    /**
     * Execute the interaction
     *
     * @return void
     */
    public function execute()
    {
        return AddressBuilder::save($this->params);
    }

}
