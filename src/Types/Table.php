<?php

namespace ElaborateCode\RowBloom\Types;

use Countable;
use ElaborateCode\RowBloom\RowBloomException;
use Iterator;

final class Table implements Countable, Iterator
{
    protected int $iteratorPosition = 0;

    public static function fromArray(array $data): static
    {
        return new self($data);
    }

    private function __construct(protected array $data)
    {
        $this->validate();
    }

    protected function validate(): void
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

        return $this;
    }

    // ============================================================
    // Iterator interface methods
    // ============================================================

    public function count(): int
    {
        return count($this->data);
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
