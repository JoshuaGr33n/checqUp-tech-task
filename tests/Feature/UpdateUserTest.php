<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use App\Domain\Users\Enums\Country;
use App\Domain\Users\Enums\Gender;
use Tests\Support\BaseTest;
use App\Models\User;

class UpdateUserTest extends BaseTest
{
    /**
     * Returns valid update data with optional overrides.
     *
     * @param array $overrides
     * @return array
     */
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

    /**
     * @var User
     */
    protected User $user;

    /**
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createTestUser([
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'original@email.com',
            'phone' => '1234567890',
            'country' => Country::CANADA->value,
            'password' => 'password123'
        ]);
    }

    /**
     * Generates the update URL for the given or current user ID.
     *
     * @param int|null $id
     * @return string
     */
    protected function updateUrl(?int $id = null): string
    {
        return $this->baseUrl . '/' . ($id ?? $this->user->id);
    }

    /**
     * Test successful user update with valid data.
     *
     * @return void
     */
    public function test_it_can_update_a_user()
    {
        $updateData = $this->validUpdateData(['phone' => '9876543210']);

        $response = $this->putJson($this->updateUrl(), $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User updated successfully!',
                'data' => [
                    'name' => 'John Updated',
                    'surname' => 'Doe Updated',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
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
        $response = $this->putJson($this->updateUrl(), [
            'name' => 'New Name'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'New Name',
            'email' => $this->user->email
        ]);
    }

    /**
     * Test validation when required fields are missing.
     *
     * @return void
     */
    public function test_it_validates_required_fields()
    {
        $response = $this->putJson($this->updateUrl(), [
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
        $response = $this->putJson($this->updateUrl(), $this->validUpdateData([
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
        $response = $this->putJson($this->updateUrl(), $this->validUpdateData([
            'phone' => '123'
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
        $response = $this->putJson($this->updateUrl(), $this->validUpdateData([
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
        // Test null case
        $response = $this->putJson($this->updateUrl(), $this->validUpdateData([
            'profile_picture' => null,
            'phone' => '9876543210'
        ]));
        $response->assertStatus(200);

        // Test with file upload
        $response = $this->putJson($this->updateUrl(), $this->validUpdateData([
            'profile_picture' => UploadedFile::fake()->image('avatar.jpg'),
            'phone' => '9876543211'
        ]));
        $response->assertStatus(200);
    }

    /**
     * Test handling of invalid ID format.
     *
     * @return void
     */
    public function test_it_returns_proper_error_for_invalid_id_format()
    {
        $response = $this->putJson($this->baseUrl . '/invalid-id');
        $response->assertStatus(400);
    }
}