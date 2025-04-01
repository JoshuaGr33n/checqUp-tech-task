<?php

namespace Tests\Unit\Domain\Users\Factories;

use App\Domain\Users\Factories\UserFactory;
use App\Domain\Users\Enums\Country;
use App\Domain\Users\Enums\Gender;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UserFactoryTest extends TestCase
{
    /**
     * Test that a user is created with the required fields.
     *
     * @return void
     */
    #[Test]
    public function it_creates_user_with_required_fields()
    {
        $user = UserFactory::createUser([
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'country' => Country::USA->value,
            'gender' => Gender::MALE->value,
            'password' => 'secret',
        ]);

        $this->assertEquals('John', $user->getName());
    }

    /**
     * Test that an exception is thrown when required fields are missing.
     *
     * @return void
     */
    #[Test]
    public function it_throws_exception_for_missing_required_fields()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        UserFactory::createUser([
            'name' => 'John',
        ]);
    }
}
