<?php

namespace App\Model;

class Node
{

    public function __construct(
        public readonly mixed $value,
        public ?Node $next = null
    )
    {
    }
}