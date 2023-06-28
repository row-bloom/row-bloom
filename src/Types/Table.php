<?php

namespace ElaborateCode\RowBloom\Types;

use Exception;
use Iterator;

class Table implements Iterator
{
    protected int $iteratorPosition = 0;

    public static function fromArray(array $table): static
    {
        return new static($table);
    }

    final public function __construct(protected array $table)
    {
        $this->validate();
    }

    protected function validate()
    {
        foreach ($this->table as $i => $row) {
            if (! is_array($row)) {
                throw new Exception("Row $i must be an array");
            }

            // ? is every cell value, a string
        }
    }

    public function toArray(): array
    {
        return $this->table;
    }

    // public function prepend(Table $table): static
    // {
    //     $this->table = array_merge($table, $this->table);
    //     return $this;
    // }

    public function append(Table $table): static
    {
        foreach ($table as $newRow) {
            $this->table[] = $newRow;
        }

        return $this;
    }

    // ============================================================
    // Iterator interface methods
    // ============================================================

    public function rewind(): void
    {
        $this->iteratorPosition = 0;
    }

    public function current(): ?array
    {
        return $this->table[$this->iteratorPosition];
    }

    public function key(): int
    {
        return $this->iteratorPosition;
    }

    public function next(): void
    {
        $this->iteratorPosition++;
    }

    public function valid(): bool
    {
        return isset($this->table[$this->iteratorPosition]);
    }
}
