<?php

namespace App\Domain\Users\Exceptions;

class UserNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('User not found', 404);
    }
}