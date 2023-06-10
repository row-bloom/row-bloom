<?php

namespace ElaborateCode\RowBloom\Types;

use Exception;

class Table
{
    public function __construct(protected array $table)
    {
        $this->validate();
    }

    protected function validate()
    {
        foreach ($this->table as $i => $row) {
            if (!is_array($row)) {
                throw new Exception("Row must $i be an array");
            }

            // foreach ($row as $j => $cell) {
            //     // TODO: throw if not primitive or serialize
            // }
        }
    }

    public function toArray(): array
    {
        return $this->table;
    }
}
