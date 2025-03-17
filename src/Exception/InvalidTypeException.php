<?php

namespace App\Exception;

class InvalidTypeException extends \Exception
{
    public function __construct(string $message = "It is not possible to add a value of another type to the list", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}