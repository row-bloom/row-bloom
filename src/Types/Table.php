<?php

namespace RowBloom\RowBloom\Types;

use Countable;
use Iterator;
use RowBloom\RowBloom\RowBloomException;

final class Table implements Countable, Iterator
{
    private int $iteratorPosition = 0;

    private int $count = 0;

    public static function fromArray(array $data): static
    {
        return new self($data);
    }

    private function __construct(private array $data)
    {
        $this->validate();

        $this->count = count($this->data);
    }

    private function validate(): void
    {
        foreach ($this->data as $i => $row) {
            if (! is_array($row)) {
                throw new RowBloomException("Row $i must be an array");
            }

            // ? is every cell value, a string
        }
    }

    public function toArray(): array
    {
        return $this->data;
    }

    // public function prepend(Table $data): static
    // {
    //     $this->data = array_merge($data, $this->data);
    //     return $this;
    // }

    public function append(Table $data): static
    {
        foreach ($data as $newRow) {
            $this->data[] = $newRow;
        }

        $this->count = count($this->data);

        return $this;
    }

    // ============================================================
    // Iterator + Countable interface methods
    // ============================================================

    public function count(): int
    {
        return $this->count;
    }

    public function rewind(): void
    {
        $this->iteratorPosition = 0;
    }

    public function current(): ?array
    {
        return $this->data[$this->iteratorPosition];
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
        return isset($this->data[$this->iteratorPosition]);
    }
}
