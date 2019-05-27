<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Store;
use Faker\Generator as Faker;

$factory->define(Store::class, function (Faker $faker) {
    return [

            'store_number'       => 123,
            'name'               => 'Test',
            'site_id'            => 123,
            'phone_number'       => '12365',
            'manager'            => null,
            'cfslocation'        => null,
            'delivery_lead_time' => null,
            'cfs_flag'           => false,
            'standardhours'      => '{[]}',
            'address_id'         => 1,

    ];
});
