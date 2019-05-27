<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\ImportLog;
use Faker\Generator as Faker;

$factory->define(ImportLog::class, function (Faker $faker) {
    return [
        'filename'=> 'test.xml'
    ];
});
