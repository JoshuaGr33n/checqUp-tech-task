<?php

namespace Tests\Support;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Domain\Users\Enums\Country;
use App\Domain\Users\Enums\Gender;

abstract class BaseTest extends TestCase
{
    use RefreshDatabase;

    protected string $baseUrl = '/api/v1/users';

    /**
     * Creates a test user with default or overridden attributes.
     *
     * @param array $overrides
     * @return User
     */
    protected function createTestUser(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'country' => Country::USA->value,
            'gender' => Gender::MALE->value,
            'profile_picture' => 'profiles/john.jpg',
            'introduction' => 'Hello world'
        ], $overrides));
    }

    /**
     * Creates multiple test users with default or overridden attributes.
     *
     * @param array $overrides1
     * @param array $overrides2
     * @return array
     */
    protected function createTestUsers(array $overrides1 = [], array $overrides2 = []): array
    {
        $user1 = $this->createTestUser($overrides1);
        
        $user2 = User::factory()->create(array_merge([
            'name' => 'Jane',
            'surname' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => '+9876543210',
            'country' => Country::CANADA->value,
            'gender' => Gender::FEMALE->value,
            'profile_picture' => 'profiles/jane.jpg',
            'introduction' => 'Hi there'
        ], $overrides2));

        return [$user1, $user2];
    }
}