<?php

namespace App\Model;

use App\Exception\InvalidTypeException;
use App\Service\AbstractSortedLinkedList;

class StringSortedLinkedList extends AbstractSortedLinkedList
{
    protected function validateType(mixed $value): void
    {
        if (!is_string($value)) {
            throw new InvalidTypeException(
                sprintf("Value must be of type string, %s added", gettype($value))
            );
        }
    }

    protected function compare(mixed $a, mixed $b): int
    {
        return strcasecmp($a, $b);
    }
}