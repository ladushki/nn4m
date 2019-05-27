<?php

namespace App\Services;

use App\Repositories\AddressRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressImportService extends ImportService
{

    public $address;
    public $addressRepository;

    public function __construct(Request $request = null, AddressRepository $addressRepository = null)
    {
        parent::__construct($request);
        $this->addressRepository = $addressRepository;
    }

    public function rules(): array
    {
        return [
            'address_line_1' => 'required',
            'lat'            => 'required',
            'lon'            => 'required',
        ];
    }

    /**
     * @param $row
     *
     * @return array
     */
    public function map($row): array
    {
        if (!isset($row['address'], $row['coordinates'])) {
            return [];
        }

        $address     = $row['address'];
        $coordinates = $row['coordinates'];

        return [
            'address_line_1' => !empty($address['address_line_1']) ? $address['address_line_1'] : null,
            'address_line_2' => !empty($address['address_line_2']) ? $address['address_line_2'] : null,
            'address_line_3' => !empty($address['address_line_3']) ? $address['address_line_3'] : null,
            'city'           => !empty($address['city']) ? $address['city'] : null,
            'county'         => !empty($address['county']) ? $address['county'] : null,
            'country'        => !empty($address['country']) ? $address['country'] : null,
            'lat'            => !empty($coordinates['lat']) ? $coordinates['lat'] : null,
            'lon'            => !empty($coordinates['lon']) ? $coordinates['lon'] : null,
        ];
    }

    public function run($row)
    {
        $item = $this->map($row);

        $validator = Validator::make($item, $this->rules());

        if ($validator->fails()) {
            return false;
        }

        if ($this->exists($item)) {
            $id = $this->update($item);
        } else {
            $id = $this->create($item);
        }

        return $id;
    }

    public function exists($item): bool
    {
        $this->address = $this->addressRepository->getAddressByCoordinates($item['lon'], $item['lat']);

        return (bool)$this->address;
    }

    public function create($row)
    {
        return $this->addressRepository->createGetId($row);
    }

    public function update($item)
    {
        return $this->addressRepository->update($item);
    }


}
