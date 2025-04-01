<?php

namespace App\Domain\Users\ValueObjects;

use InvalidArgumentException;

class PhoneNumber
{
    private string $phone;

    public function __construct(string $phone)
    {
        if (!preg_match('/^\+?[0-9]{7,15}$/', $phone)) {
            throw new InvalidArgumentException("Invalid phone number format.");
        }
        $this->phone = $phone;
    }

    public function getValue(): string
    {
        return $this->phone;
    }
}
