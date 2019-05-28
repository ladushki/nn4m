<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Exceptions\InvalidContentException;

class ImportService
{
    protected $request;
    protected $content;

    public $filename;

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
        set_time_limit(0);

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

}
