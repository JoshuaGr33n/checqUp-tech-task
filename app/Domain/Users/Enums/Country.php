<?php

namespace App\Domain\Users\Enums;

enum Country: string
{
    case USA = 'USA';
    case CANADA = 'Canada';
    case UK = 'UK';
    case GERMANY = 'Germany';
    case FRANCE = 'France';

    public static function isValid(string $value): bool
    {
        return !is_null(self::tryFrom($value));
    }
}