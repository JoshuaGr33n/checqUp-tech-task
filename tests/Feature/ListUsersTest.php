<?php

namespace Tests\Feature;

use Tests\Support\BaseTest;

class ListUsersTest extends BaseTest
{
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
        $response->assertJsonCount(2, 'data');
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
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['name' => $user1->name]);
        $response->assertJsonMissing(['name' => $user2->name]);
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
        $response->assertJsonCount(1, 'data');
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
        $response->assertJsonCount(0, 'data');
    }
}