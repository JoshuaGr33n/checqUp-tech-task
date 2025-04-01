<?php

namespace App\Domain\Users\Entities;

use App\Domain\Users\ValueObjects\Email;
use App\Domain\Users\ValueObjects\PhoneNumber;
use App\Domain\Users\Enums\Country;
use App\Domain\Users\Enums\Gender;

class User
{
    private ?int $id;
    private string $name;
    private string $surname;
    private Email $email;
    private PhoneNumber $phone;
    private Country $country;
    private Gender $gender;
    private string $password;
    private ?string $profilePicture;
    private ?string $introduction;
    private ?\DateTimeInterface $created_at;

    public function __construct(
        ?int $id,
        string $name,
        string $surname,
        Email $email,
        PhoneNumber $phone,
        Country $country,
        Gender $gender,
        string $password,
        ?string $profilePicture = null,
        ?string $introduction = null,
        ?\DateTimeInterface $created_at = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->phone = $phone;
        $this->country = $country;
        $this->gender = $gender;
        $this->password = $password;
        $this->profilePicture = $profilePicture;
        $this->introduction = $introduction;
        $this->created_at = $created_at;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPhone(): PhoneNumber
    {
        return $this->phone;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function getGender(): Gender
    {
        return $this->gender;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }
}
