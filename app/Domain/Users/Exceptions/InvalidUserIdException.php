<?php

namespace App\Domain\Users\Exceptions;

class InvalidUserIdException extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('User ID must be a positive integer', 400);
    }
}


