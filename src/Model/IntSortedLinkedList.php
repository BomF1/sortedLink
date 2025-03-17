<?php

namespace App\Model;

use App\Exception\InvalidTypeException;
use App\Service\AbstractSortedLinkedList;

class IntSortedLinkedList extends AbstractSortedLinkedList
{

    protected function validateType(mixed $value): void
    {
        if (!is_int($value)) {
            throw new InvalidTypeException(
                sprintf("Value must by of type int, %s added", gettype($value))
            );
        }
    }

    protected function compare(mixed $a, mixed $b): int
    {
        return $a <=> $b;
    }

}