<?php

namespace App\Services;

use App\Interactions\CreateAddress;
use App\Interactions\CreateStore;
use Illuminate\Support\Facades\DB;
use ImportErrorLogger;

class StoreImportService extends ImportService
{
    public $log;

    public function map($row): array
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

    public function mapAddress($row): array
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

    public function getAddressId($row)
    {
        $addressData = $this->mapAddress(array_filter($row));

        $address = CreateAddress::run($addressData);

        if ($address->valid) {
            return $address->result->id;
        }

        return false;
    }

    public function run()
    {
        $this->status['filename'] = $this->getFilename();

        $content = $this->getContent();

        DB::beginTransaction();
        try {
            ImportErrorLogger::saveLog($this->status);
            $this->importFromArray(current($content));

            if (($this->status['updated'] + $this->status['inserted']) === 0) {
                DB::rollBack();
                return false;
            } else {
                DB::commit();
                $this->status['is_completed'] = true;
                ImportErrorLogger::saveLog($this->status);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
        $this->log = ImportErrorLogger::findLog();
        return $this;
    }


    public function importFromArray($data)
    {
        $counter = 0;

        collect($data)->each(function ($row) use (&$counter) {
            $counter++;
            $storeData = $this->map(array_filter($row));
            $outcome = CreateStore::run($storeData);

            if ($outcome->valid) {
                if ($outcome->result->wasRecentlyCreated) {
                    $this->status['inserted']++;
                } else {
                    $this->status['updated']++;
                }
                return [
                    'success' => true,
                    'store'   => $outcome->result,
                ];
            }

            $this->status['failed']++;

            $key = !empty($row['number']) ? $row['number'] : 0;
            $this->status['errors'][$key] = $outcome->errors->toArray();

            ImportErrorLogger::saveErrors($this->status['errors']);

            return [
                'success' => false,
                'errors'  => $this->status['errors'][$key],
            ];
        });

        return $this->status;
    }
}
