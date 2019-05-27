<?php

namespace App\Services;

use App\Repositories\AddressRepository;
use App\Repositories\StoreRepository;
use App\Store;
use Illuminate\Http\Request;

class StoreImportService extends ImportService
{

    public $store;
    public $storeRepository;
    public $importLogRepository;
    public $addressRepository;

    public function __construct(
        Request $request,
        StoreRepository $storeRepository,
        AddressRepository $addressRepository
    ) {
        parent::__construct($request);
        $this->storeRepository = $storeRepository;
        $this->addressRepository = $addressRepository;
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|max:255',
            'address_id'   => 'required',
            'store_number' => 'required|integer',
            'site_id'      => 'required',
            'phone_number' => 'required',
            'cfs_flag'     => 'required|integer',
        ];
    }

    /**
     * @param $row
     *
     * @return array
     */
    public function map($row, $counter = 0): array
    {

        $addressId = $this->getAddressId($row);

        $arr = [
            'store_number'       => !empty($row['number']) ? $row['number'] : null,
            'name'               => !empty($row['name']) ? $row['name'] : null,
            'site_id'            => !empty($row['siteid']) ? $row['siteid'] : null,
            'phone_number'       => !empty($row['phone_number']) ? $row['phone_number'] : null,
            'manager'            => !empty($row['manager']) ? $row['manager'] : null,
            'cfslocation'        => !empty($row['cfslocation']) ? $row['cfslocation'] : null,
            'delivery_lead_time' => !empty($row['delivery_lead_time']) ? $row['delivery_lead_time'] : null,
            'cfs_flag'           => !empty($row['cfs_flag']),
            'standardhours'      => !empty($row['standardhours']) ? json_encode($row['standardhours']) : null,
            'address_id'         => $addressId,
        ];

        return $arr;
    }

    public function getAddressId($row)
    {
        $addressImport = new AddressImportService($this->request, $this->addressRepository);

        if ($address = $addressImport->run($row)) {
            return $address;
        }

        return false;
    }

    public function exists($item): bool
    {
        $this->store = $this->storeRepository->getStoreByNumber((int)$item['store_number']);

        return (bool)$this->store;
    }

    public function create($row): Store
    {
        $this->store = $this->storeRepository->create($row);

        return $this->store;
    }

    public function update($item): Store
    {
        return $this->storeRepository->update($item);
    }
}
