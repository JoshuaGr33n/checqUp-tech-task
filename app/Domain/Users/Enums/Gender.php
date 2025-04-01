<?php

namespace App\Domain\Users\Enums;

enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';

    public static function isValid(string $value): bool
    {
        return !is_null(self::tryFrom($value));
    }
}