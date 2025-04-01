<?php

namespace Tests\Feature;

use App\Models\User;
use App\Domain\Users\Enums\Country;
use App\Domain\Users\Enums\Gender;
use Tests\Support\BaseTest;

class ViewUserDetailsTest extends BaseTest
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
     * Test successful retrieval of user details.
     *
     * @return void
     */
    public function test_it_shows_user_details()
    {
        $user = $this->createTestUser();
        
        $response = $this->getJson($this->baseUrl."/{$user->id}");
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'surname',
                    'email',
                    'phone',
                    'country',
                    'gender',
                    'profile_picture',
                    'introduction'
                ]
            ])
            ->assertJsonFragment([
                'name' => 'John',
                'country' => Country::USA->value
            ]);
    }

    /**
     * Test handling of nullable fields when empty.
     *
     * @return void
     */
    public function test_it_shows_nullable_fields_when_empty()
    {
        $user = $this->createTestUser([
            'profile_picture' => null,
            'introduction' => null
        ]);
        
        $response = $this->getJson($this->baseUrl."/{$user->id}");
        
        $response->assertJsonPath('data.profile_picture', null)
                ->assertJsonPath('data.introduction', null);
    }

    /**
     * Test handling of non-existent user ID.
     *
     * @return void
     */
    public function test_it_returns_404_for_nonexistent_user()
    {
        $response = $this->getJson($this->baseUrl.'/9999');
        $response->assertStatus(404);
    }

    /**
     * Test handling of invalid ID format.
     *
     * @return void
     */
    public function test_it_returns_proper_error_for_invalid_id_format()
    {
        $response = $this->getJson($this->baseUrl.'/invalid-id');
        $response->assertStatus(400); 
    }
}