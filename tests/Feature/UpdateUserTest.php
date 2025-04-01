<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use App\Domain\Users\Enums\Country;
use App\Domain\Users\Enums\Gender;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\Support\BaseTest;

class UpdateUserTest extends BaseTest
{
 
    protected function validUpdateData($overrides = [])
    {
        return array_merge([
            'name' => 'John Updated',
            'surname' => 'Doe Updated',
            'email' => 'updated' . uniqid() . '@email.com',
            'phone' => '98765' . rand(1000, 9999),
            'country' => Country::USA->value,
            'gender' => Gender::MALE->value,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'profile_picture' => null,
            'introduction' => 'Updated bio'
        ], $overrides);
    }

    protected function createTestUser()
    {
         // create test user
        return User::factory()->create([
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'original@email.com',
            'phone' => '1234567890',
            'country' => Country::CANADA->value,
            'gender' => Gender::MALE->value,
            'password' => Hash::make('password123'),
        ]);
    }

    /**
     * Test successful user update with valid data.
     *
     * @return void
     */
    public function test_it_can_update_a_user()
    {
        $user = $this->createTestUser();
        $updateData = $this->validUpdateData(['phone' => '9876543210']); // Ensure phone is valid length

        $response = $this->putJson($this->baseUrl."/{$user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User updated successfully!',
                'data' => [
                    'name' => 'John Updated',
                    'surname' => 'Doe Updated',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'John Updated',
            'email' => $updateData['email'],
        ]);
    }

    /**
     * Test partial updates with only some fields.
     *
     * @return void
     */
    public function test_partial_updates_allowed()
    {
        $user = $this->createTestUser();

        $response = $this->putJson($this->baseUrl."/{$user->id}", [
            'name' => 'New Name' // Only update name
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => $user->email
        ]);
    }

    /**
     * Test validation when required fields are missing.
     *
     * @return void
     */
    public function test_it_validates_required_fields()
    {
        $user = $this->createTestUser();

        $response = $this->putJson($this->baseUrl."/{$user->id}", [
            'name' => '',
            'surname' => '',
            'email' => '',
            'phone' => '',
            'country' => '',
            'gender' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'surname',
                'email',
                'phone',
                'country',
                'gender'
            ]);
    }

    /**
     * Test validation for invalid email format.
     *
     * @return void
     */
    public function test_it_validates_email_format()
    {
        $user = $this->createTestUser();

        $response = $this->putJson($this->baseUrl."/{$user->id}", $this->validUpdateData([
            'email' => 'invalid-email'
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test validation for invalid phone format.
     *
     * @return void
     */
    public function test_it_validates_phone_format()
    {
        $user = $this->createTestUser();

        $response = $this->putJson($this->baseUrl."/{$user->id}", $this->validUpdateData([
            'phone' => '123' // Too short
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone']);
    }

    /**
     * Test validation for password confirmation.
     *
     * @return void
     */
    public function test_it_validates_password_confirmation()
    {
        $user = $this->createTestUser();

        $response = $this->putJson($this->baseUrl."/{$user->id}", $this->validUpdateData([
            'password' => 'newpassword123',
            'password_confirmation' => 'mismatch'
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Test optional profile picture field acceptance.
     *
     * @return void
     */
    public function test_profile_picture_can_be_null_or_present()
    {
        $user = $this->createTestUser();

        // Test null case
        $response = $this->putJson($this->baseUrl."/{$user->id}", $this->validUpdateData([
            'profile_picture' => null,
            'phone' => '9876543210' // Ensure valid phone
        ]));
        $response->assertStatus(200);

        // Test with file upload
        $response = $this->putJson($this->baseUrl."/{$user->id}", $this->validUpdateData([
            'profile_picture' => UploadedFile::fake()->image('avatar.jpg'),
            'phone' => '9876543211' // Ensure unique phone
        ]));
        $response->assertStatus(200);
    }

    /**
     * Test optional introduction field acceptance.
     *
     * @return void
     */
    public function test_introduction_can_be_null_or_present()
    {
        $user = $this->createTestUser();

        // Test null case
        $response = $this->putJson($this->baseUrl."/{$user->id}", $this->validUpdateData([
            'introduction' => null,
            'phone' => '9876543210' // Ensure valid phone
        ]));
        $response->assertStatus(200);

        // Test with value
        $response = $this->putJson($this->baseUrl."/{$user->id}", $this->validUpdateData([
            'introduction' => 'New introduction',
            'phone' => '9876543211' // Ensure unique phone
        ]));
        $response->assertStatus(200);
    }

    /**
     * Test prevention of duplicate email during update.
     *
     * @return void
     */
    public function test_it_prevents_duplicate_email()
    {
        $user1 = $this->createTestUser();
        $user2 = User::factory()->create([
            'name' => 'Test',
            'surname' => 'User',
            'email' => 'existing@email.com',
            'phone' => '0987654321',
            'country' => Country::USA->value,
            'gender' => Gender::MALE->value,
            'password' => Hash::make('password123')
        ]);

        $response = $this->putJson($this->baseUrl."/{$user1->id}", $this->validUpdateData([
            'email' => 'existing@email.com',
            'phone' => '9876543210' // Ensure unique phone
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test prevention of duplicate phone during update.
     *
     * @return void
     */
    public function test_it_prevents_duplicate_phone()
    {
        $user1 = $this->createTestUser();
        $user2 = User::factory()->create([
            'name' => 'Test',
            'surname' => 'User',
            'email' => 'another@email.com',
            'phone' => '9876543210',
            'country' => Country::USA->value,
            'gender' => Gender::MALE->value,
            'password' => Hash::make('password123')
        ]);

        $response = $this->putJson($this->baseUrl."/{$user1->id}", $this->validUpdateData([
            'phone' => '9876543210',
            'email' => 'unique' . uniqid() . '@email.com' // Ensure unique email
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone']);
    }

    /**
     * Test handling of non-existent user ID.
     *
     * @return void
     */
    public function test_it_returns_404_for_nonexistent_user()
    {
        $response = $this->putJson($this->baseUrl.'/9999');
        $response->assertStatus(404);
    }

    /**
     * Test handling of invalid ID format.
     *
     * @return void
     */
    public function test_it_returns_proper_error_for_invalid_id_format()
    {
        $response = $this->putJson($this->baseUrl.'/invalid-id');
        $response->assertStatus(400); 
    }
}