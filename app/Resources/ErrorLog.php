<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErrorLog extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'store_number' => $this->store_number,
            'tag'          => $this->column_name,
            'description'  => $this->description,
            'date'         => $this->updated_at,
        ];
    }
}
