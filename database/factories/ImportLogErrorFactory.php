<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\ImportLogError;
use Faker\Generator as Faker;

$factory->define(ImportLogError::class, function (Faker $faker) {
    return [
        'store_number'=> '0'
    ];
});
