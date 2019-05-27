<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Store extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'store_number' => $this->store_number,
            'name' => $this->name,
            'site_id' => $this->site_id,
            'phone_number' => $this->phone_number,
            'manager' => $this->manager,
            'cfslocation' => $this->cfslocation,
            'delivery_lead_time' => $this->delivery_lead_time,
            'standardhours' => $this->standardhours,
            'lat' => optional($this->address)->lat,
            'lon' => optional($this->address)->lon,
            'address_line_1' => optional($this->address)->address_line_1,
            'address_line_2' => optional($this->address)->address_line_2,
            'address_line_3' => optional($this->address)->address_line_3,
            'city' => optional($this->address)->city,
            'county' => optional($this->address)->county,
            'postcode' => optional($this->address)->postcode,
            'modified' => $this->updated_at,
        ];
    }
}
