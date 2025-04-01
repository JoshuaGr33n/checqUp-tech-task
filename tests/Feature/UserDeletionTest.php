<?php

namespace Tests\Feature;

use App\Models\User;
use App\Domain\Users\Enums\Country;
use App\Domain\Users\Enums\Gender;
use Tests\Support\BaseTest;

class UserDeletionTest extends BaseTest
{

    protected function createTestUser(array $overrides = []): User
    {
        // create test user
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
     * Test successful user deletion.
     *
     * @return void
     */
    public function test_it_can_delete_a_user_successfully()
    {
        $user = $this->createTestUser();

        $response = $this->deleteJson(route('users.destroy', ['user' => $user->id])); //check success

        $response->assertStatus(200)
                 ->assertJson(['message' => 'User deleted successfully']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /**
     * Test handling of non-existent user ID.
     *
     * @return void
     */
    public function test_it_returns_404_if_user_does_not_exist()
    {
        $response = $this->deleteJson(route('users.destroy', ['user' => 9999])); //non-existent user id

        $response->assertStatus(404); 
    }

   /**
     * Test handling of invalid ID format.
     *
     * @return void
     */
    public function test_it_returns_proper_error_for_invalid_id_format()
    {
        $response = $this->deleteJson($this->baseUrl.'/invalid-id'); //invalid user id
        $response->assertStatus(400); 
    }
}