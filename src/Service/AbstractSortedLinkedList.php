<?php

namespace App\Service;

use App\Model\Node;

abstract class AbstractSortedLinkedList implements SortedLinkedListInterface
{
    protected ?Node $head = null;
    protected int $count = 0;

    public function add(mixed $value): bool
    {
        $this->validateType($value);

        $newNode = new Node($value);
        $this->count++;

        if ($this->head === null || $this->compare($value, $this->head->value) < 0) {
            $newNode->next = $this->head;
            $this->head = $newNode;
            return true;
        }

        $current = $this->head;
        while ($current->next !== null && $this->compare($value, $current->next->value) > 0) {
            $current = $current->next;
        }

        $newNode->next = $current->next;
        $current->next = $newNode;

        return true;
    }

    public function remove(mixed $value): bool
    {
        if ($this->head === null) {
            return false;
        }

        if ($this->compare($this->head->value, $value) === 0) {
            $this->head = $this->head->next;
            $this->count--;
            return true;
        }

        $current = $this->head;
        while ($current->next !== null && $this->compare($current->next->value, $value) !== 0) {
            $current = $current->next;
        }

        if ($current->next !== null) {
            $current->next = $current->next->next;
            $this->count--;
            return true;
        }

        return false;
    }

    public function contains(mixed $value): bool
    {
        if ($this->head === null) {
            return false;
        }

        $current = $this->head;
        while ($current !== null) {
            if ($this->compare($current->value, $value) === 0) {
                return true;
            }

            if ($this->compare($current->value, $value) > 0) {
                return false;
            }

            $current = $current->next;
        }

        return false;
    }

    public function count(): int
    {
        return $this->count;
    }

    public function toArray(): array
    {
        $result = [];
        $current = $this->head;

        while ($current !== null) {
            $result[] = $current->value;
            $current = $current->next;
        }

        return $result;
    }

    public function clear(): void
    {
        $this->head = null;
        $this->count = 0;
    }

    public function getFirst(): mixed
    {
        if ($this->head === null) {
            throw new \UnderflowException("List is empty");
        }

        return $this->head->value;
    }

    public function getLast(): mixed
    {
        if ($this->head === null) {
            throw new \UnderflowException("List is empty");
        }

        $current = $this->head;
        while ($current->next !== null) {
            $current = $current->next;
        }

        return $current->value;
    }

    public function getIterator(): \Traversable
    {
        $current = $this->head;

        while ($current !== null) {
            yield $current->value;
            $current = $current->next;
        }
    }

    abstract protected function validateType(mixed $value): void;
    abstract protected function compare(mixed $a, mixed $b): int;

}