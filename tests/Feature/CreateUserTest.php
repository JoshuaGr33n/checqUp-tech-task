<?php
namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use App\Domain\Users\Enums\Country;
use App\Domain\Users\Enums\Gender;
use Tests\Support\BaseTest;

class CreateUserTest extends BaseTest
{
   
   
    protected function validUserData($overrides = [])
    {
        return array_merge([
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'johndoe'.uniqid().'@email.com', // Make email unique
            'phone' => '123456'.rand(1000, 9999), // Make phone unique
            'country' => Country::USA->value,
            'gender' => Gender::MALE->value,
            'password' => 'password',
            'password_confirmation' => 'password',
        ], $overrides);
    }

    /**
     * Test successful user creation with valid data.
     *
     * @return void
     */
    public function test_user_can_be_created_successfully()
    {
        $response = $this->postJson($this->baseUrl, $this->validUserData());
        $response->assertStatus(201);
    }

    /**
     * Test validation when required fields are missing.
     *
     * @return void
     */
    public function test_missing_required_fields_should_return_validation_error()
    {
        $response = $this->postJson($this->baseUrl, $this->validUserData([
            'name' => '',
            'surname' => '',
        ]));
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'surname']);
    }

    /**
     * Test validation for invalid email format.
     *
     * @return void
     */
    public function test_invalid_email_should_return_validation_error()
    {
        $response = $this->postJson($this->baseUrl, $this->validUserData([
            'email' => 'invalid-email',
        ]));
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test validation for invalid phone format.
     *
     * @return void
     */
    public function test_invalid_phone_should_return_validation_error()
    {
        $response = $this->postJson($this->baseUrl, $this->validUserData([
            'phone' => '123', // Too short
        ]));
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone']);
    }

    /**
     * Test prevention of duplicate email registration.
     *
     * @return void
     */
    public function test_duplicate_email_should_return_error()
    {
        $data = $this->validUserData(['email' => 'duplicate@email.com']);
        $this->postJson($this->baseUrl, $data);
        
        $response = $this->postJson($this->baseUrl, $data);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test prevention of duplicate phone registration.
     *
     * @return void
     */
    public function test_duplicate_phone_should_return_error()
    {
        $data = $this->validUserData(['phone' => '1234567890']);
        $this->postJson($this->baseUrl, $data);
        
        $response = $this->postJson($this->baseUrl, $data);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone']);
    }

    /**
     * Test optional profile picture field acceptance.
     *
     * @return void
     */
    public function test_profile_picture_can_be_null_or_present()
    {
        // Test null case
        $response = $this->postJson($this->baseUrl, $this->validUserData([
            'profile_picture' => null
        ]));
        $response->assertStatus(201);

        // Test with actual file upload
        $response = $this->postJson($this->baseUrl, $this->validUserData([
            'profile_picture' => UploadedFile::fake()->image('avatar.jpg')
        ]));
        $response->assertStatus(201);
    }

    /**
     * Test optional introduction field acceptance.
     *
     * @return void
     */
    public function test_introduction_can_be_null_or_present()
    {
        // Test null case
        $response = $this->postJson($this->baseUrl, $this->validUserData([
            'introduction' => null
        ]));
        $response->assertStatus(201);

        // Test with introduction
        $response = $this->postJson($this->baseUrl, $this->validUserData([
            'introduction' => 'Hello world'
        ]));
        $response->assertStatus(201);
    }
}