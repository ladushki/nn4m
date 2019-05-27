<?php

namespace App\Services;

use App\Exceptions\InvalidContentException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

abstract class ImportService
{

    use ValidatesRequests;

    public $filename;

    protected $request;
    protected $content;

    public $status = [
        'filename'  => '',
        'inserted'  => 0,
        'updated'   => 0,
        'failed'    => 0,
        'errors'    => [],
        'is_completed' => false,
    ];

    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    public function load(string $filename)
    {
        if (file_exists($filename)) {
            $this->setFilename($filename);
            $xml = file_get_contents($filename);
        } else {
            throw new InvalidContentException('Unable to open file.');
        }

        return $this->resolveXmlObject($xml);
    }

    public function resolveXmlObject($xml)
    {
        if (!$xml) {
            throw new InvalidContentException('Unable to parse XML from string.');
        }

        $output = xmlToArray($xml);

        $this->setContent($output);

        return $this;
    }


    public function getFilename()
    {
        return basename($this->filename);
    }


    public function setFilename($filename): void
    {
        $this->filename = $filename;
    }

    public function getContent()
    {
        return $this->content;
    }


    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function import(): array
    {
        $this->status['filename'] = $this->getFilename();

        $content = $this->getContent();

        DB::beginTransaction();
        try {
            $this->importFromArray(current($content));

            if ($this->status['updated'] === 0 && $this->status['inserted'] === 0) {
                DB::rollBack();
            } else {
                DB::commit();
                $this->status['is_completed'] = true;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            //dd($e->getMessage());
        }

        return $this->status;
    }

    public function importFromArray($data)
    {
        $counter = 0;

        collect($data)->each(function ($row) use (&$counter) {

            $counter++;

            $item = $this->map(array_filter($row), $counter);

            $validator = Validator::make($item, $this->rules());

            if ($validator->fails()) {
                $key = !empty($row['number']) ? $row['number'] : 0;

                $this->status['failed']++;
                $this->status['errors'][$key] = $validator->errors()->toArray();

                return;
            }

            if ($this->exists($item)) {
                try {
                    $this->update($item);
                } catch (\Exception $e) {
                    $this->status['failed']++;

                    return;
                }
                $this->status['updated']++;
            } else {

                try {
                    $this->create($item);
                } catch (\Exception $e) {
                    $this->status['failed']++;

                    return;
                }

                $this->status['inserted']++;
            }
        });
        return $this->status;
    }


    abstract public function map($row);

    abstract public function rules();

    abstract public function create($item);

    abstract public function update($item);

    abstract public function exists($item);

}
