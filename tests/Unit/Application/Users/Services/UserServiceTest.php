<?php

namespace Tests\Unit\Application\Users\Services;

use App\Application\Users\Services\UserService;
use App\Domain\Users\Entities\User;
use App\Domain\Users\Repositories\UserRepositoryInterface;
use App\Application\Users\Services\FileUploadService;
use App\Domain\Users\ValueObjects\Email;
use App\Domain\Users\ValueObjects\PhoneNumber;
use App\Domain\Users\Enums\Country;
use App\Domain\Users\Enums\Gender;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Test;

class UserServiceTest extends TestCase
{
    private MockInterface&UserRepositoryInterface $userRepository;
    private MockInterface&FileUploadService $fileUploadService;
    private UserService $userService;

    /**
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->fileUploadService = Mockery::mock(FileUploadService::class);
        $this->userService = new UserService($this->userRepository, $this->fileUploadService);
    }

    /**
     * Test uploading a profile picture during user creation.
     *
     * @return void
     */
    #[Test]
    public function it_uploads_profile_picture_during_creation(): void
    {
        $file = UploadedFile::fake()->image('avatar.jpg');
        $data = [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'country' => 'USA',
            'gender' => 'male',
            'password' => 'secret',
            'profile_picture' => $file,
        ];

        $this->fileUploadService->shouldReceive('upload')->once()->with($file)->andReturn('profiles/avatar.jpg');

        $mockUser = new User(1, 'John', 'Doe', new Email('john@example.com'), new PhoneNumber('+1234567890'), Country::USA, Gender::MALE, 'hashed_password', 'profiles/avatar.jpg', null, null);
        $this->userRepository->shouldReceive('create')->once()->andReturn($mockUser);

        $user = $this->userService->createUser($data);
        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * Test deleting the old profile picture during user update.
     *
     * @return void
     */
    #[Test]
    public function it_deletes_old_profile_picture_during_update(): void
    {
        $this->userRepository->shouldReceive('exists')->once()->with(1)->andReturn(true);
        $oldUser = new User(1, 'John', 'Doe', new Email('john@example.com'), new PhoneNumber('+1234567890'), Country::USA, Gender::MALE, 'hashed_password', 'profiles/old.jpg', null, null);
        $file = UploadedFile::fake()->image('new.jpg');
        $data = ['profile_picture' => $file];

        $this->userRepository->shouldReceive('find')->once()->with(1)->andReturn($oldUser);
        $this->fileUploadService->shouldReceive('delete')->once()->with('profiles/old.jpg')->andReturn(true);
        $this->fileUploadService->shouldReceive('upload')->once()->with($file)->andReturn('profiles/new.jpg');

        $updatedUser = new User(1, 'John', 'Doe', new Email('john@example.com'), new PhoneNumber('+1234567890'), Country::USA, Gender::MALE, 'hashed_password', 'profiles/new.jpg', null, null);
        $this->userRepository->shouldReceive('update')->once()->with(1, Mockery::type(User::class))->andReturn($updatedUser);

        $result = $this->userService->updateUser(1, $data);
        $this->assertInstanceOf(User::class, $result);
    }

    /**
     * Clean up after each test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
