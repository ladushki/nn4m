<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public $fillable = [
        'address_line_1',
        'address_line_2',
        'address_line_3',
        'city',
        'county',
        'country',
        'lat',
        'lon',
        'postcode',
    ];

}
