<?php

namespace App\Service;

use App\Model\IntSortedLinkedList;
use App\Model\StringSortedLinkedList;

class SortedLinkedListFactory
{

    public static function createStringList(): StringSortedLinkedList
    {
        return new StringSortedLinkedList();
    }

    public static function createIntList(): IntSortedLinkedList
    {
        return new IntSortedLinkedList();
    }
}