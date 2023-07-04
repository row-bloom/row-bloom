<?php

namespace ElaborateCode\RowBloom\DataCollectors\Json;

use ElaborateCode\RowBloom\DataCollectorContract;
use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Types\Table;
use Exception;

class JsonDataCollector implements DataCollectorContract
{
    public function getTable(File|string $file): Table
    {
        // ! make() blocks using mocked instance in container
        $file = $file instanceof File ? $file : app()->make(File::class, ['path' => $file]);

        $file->mustExist()->mustBeReadable()->mustBeFile()->mustBeExtension('json');

        $data = json_decode($file->readFileContent(), true);

        if (! is_array($data)) {
            throw new Exception("Invalid Json '{$file}'");
        }

        return Table::fromArray($data);
    }
}
