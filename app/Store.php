<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Store extends Model
{

    public $fillable = [
        'name',
        'address_id',
        'store_number',
        'site_id',
        'phone_number',
        'cfs_flag',
        'address_id',
        'manager',
        'cfslocation',
        'standardhours',
        'delivery_lead_time',
    ];

    /**
     * @return HasOne
     */
    public function address(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }

    public function getStandardhoursAttribute($value)
    {
        return json_decode($value);
    }
}
