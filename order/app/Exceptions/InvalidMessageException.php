<?php

namespace App\Exceptions;

use Exception;

class InvalidMessageException extends Exception
{
    public function __construct(
        string $message,
        public readonly array $errors = [],
        public readonly array $data = []
    ) {
        parent::__construct($message);
    }
}
