<?php

namespace Tests\Feature;

use Tests\Support\BaseTest;

class UserDeletionTest extends BaseTest
{
    /**
     * Test successful user deletion.
     *
     * @return void
     */
    public function test_it_can_delete_a_user_successfully()
    {
        $user = $this->createTestUser();

        $response = $this->deleteJson(route('users.destroy', ['user' => $user->id]));

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
        $response = $this->deleteJson(route('users.destroy', ['user' => 9999]));

        $response->assertStatus(404); 
    }

    /**
     * Test handling of invalid ID format.
     *
     * @return void
     */
    public function test_it_returns_proper_error_for_invalid_id_format()
    {
        $response = $this->deleteJson($this->baseUrl.'/invalid-id');
        $response->assertStatus(400); 
    }
}