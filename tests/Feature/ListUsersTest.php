<?php

namespace Tests\Feature;

use App\Models\User;
use App\Domain\Users\Enums\Country;
use App\Domain\Users\Enums\Gender;
use Tests\Support\BaseTest;

class ListUsersTest extends BaseTest
{
    protected function createTestUsers(array $overrides1 = [], array $overrides2 = []): array
    {  // test users
        $user1 = User::factory()->create(array_merge([
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'country' => Country::USA->value,
            'gender' => Gender::MALE->value,
            'profile_picture' => 'profiles/john.jpg',
            'introduction' => 'Hello world'
        ], $overrides1));

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
    /**
     * Test fetching all users without any filters.
     *
     * @return void
     */
    public function test_fetch_all_users()
    {
        $users = $this->createTestUsers();
        $user1 = $users[0];
        $user2 = $users[1];
    
        $response = $this->getJson($this->baseUrl);
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');  // Ensure two users are returned
        $response->assertJsonFragment(['name' => $user1->name]);
        $response->assertJsonFragment(['country' => $user1->country]);
        $response->assertJsonFragment(['name' => $user2->name]);
        $response->assertJsonFragment(['country' => $user2->country]);
    }

    /**
     * Test fetching users by a specific filter 
     *
     * @return void
     */
    public function test_fetch_users_by_name_filter()
    {
        $users = $this->createTestUsers();
        $user1 = $users[0];
        $user2 = $users[1];

        $response = $this->getJson($this->baseUrl.'?name=John');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');  // Ensure only 1 user is returned
        $response->assertJsonFragment(['name' => $user1->name]);  // Ensure John Doe is included
        $response->assertJsonMissing(['name' => $user2->name]);  // Ensure Jane Smith is not included
    }

    /**
     * Test fetching users with multiple filters.
     *
     * @return void
     */
    public function test_fetch_users_with_multiple_filters()
    {
        $users = $this->createTestUsers();
        $user1 = $users[0];
        $user2 = $users[1];

        
        $response = $this->getJson($this->baseUrl.'?name=John&country=USA');

       
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');  // Only John Doe should be returned
        $response->assertJsonFragment(['name' => $user1->name]);
        $response->assertJsonFragment(['country' => $user1->country]);
    }

    /**
     * Test fetching users with no matching results.
     *
     * @return void
     */
    public function test_fetch_users_no_results()
    {
        $response = $this->getJson($this->baseUrl.'?name=NonExistentName&country=NonExistentCountry');

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');  // No users should be returned
    }
}
