<?php

namespace App\Domain\Users\Factories;

use App\Domain\Users\Entities\User;
use App\Domain\Users\ValueObjects\Email;
use App\Domain\Users\ValueObjects\PhoneNumber;
use App\Domain\Users\Enums\Country;
use App\Domain\Users\Enums\Gender;

class UserFactory
{
    public static function createUser(array $data): User
    {
        // Only validate fields that are present in the array
        $required = ['name', 'surname', 'email', 'phone', 'country', 'gender', 'password'];

        foreach ($required as $field) {
            if (!array_key_exists($field, $data)) {
                throw new \InvalidArgumentException("Missing required field: $field");
            }
        }
        return new User(
            null,
            $data['name'],
            $data['surname'],
            new Email($data['email']),
            new PhoneNumber($data['phone']),
            Country::from($data['country']),
            Gender::from($data['gender']),
            $data['password'],
            $data['profile_picture'] ?? null,
            $data['introduction'] ?? null,
            null

        );
    }

    public static function updateUser(array $data, User $existingUser): User
    {
        return new User(
            $existingUser->getId(),
            $data['name'] ?? $existingUser->getName(),
            $data['surname'] ?? $existingUser->getSurname(),
            isset($data['email']) ? new Email($data['email']) : $existingUser->getEmail(),
            isset($data['phone']) ? new PhoneNumber($data['phone']) : $existingUser->getPhone(),
            isset($data['country']) ? Country::from($data['country']) : $existingUser->getCountry(),
            isset($data['gender']) ? Gender::from($data['gender']) : $existingUser->getGender(),
            $data['password'] ?? $existingUser->getPassword(), // Password remains unchanged if not provided
            $data['profile_picture'] ?? $existingUser->getProfilePicture(),
            $data['introduction'] ?? $existingUser->getIntroduction(),
            null
        );
    }

}
