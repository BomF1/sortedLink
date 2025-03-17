<?php

namespace App\Service;

interface SortedLinkedListInterface extends \Countable, \IteratorAggregate
{
    public function add(mixed $value): bool;
    public function remove(mixed $value): bool;
    public function contains(mixed $value): bool;
    public function clear(): void;
    public function toArray(): array;
    public function getFirst(): mixed;
    public function getLast(): mixed;

}