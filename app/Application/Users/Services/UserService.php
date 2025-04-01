<?php

namespace App\Application\Users\Services;


use App\Domain\Users\Factories\UserFactory;
use App\Domain\Users\Repositories\UserRepositoryInterface;
use App\Domain\Users\ValueObjects\UserId;
use App\Application\Users\Services\FileUploadService;
use Illuminate\Http\UploadedFile;


class UserService
{
    private UserRepositoryInterface $userRepository;
    private FileUploadService $fileUploadService;

    public function __construct(UserRepositoryInterface $userRepository, FileUploadService $fileUploadService)
    {
        $this->userRepository = $userRepository;
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Get all users
     */
    public function getAllUsers(array $filters)
    {
        $users = $this->userRepository->all($filters);
        return $users;
    }

    /**
     * Find user by ID
     */
    public function getUserById($id)
    {
        $userId = new UserId($id, $this->userRepository);
        $user = $this->userRepository->find($userId->getValue());
        return $user;
    }

    /**
     * Create a new user
     */
    public function createUser(array $data)
    {

        if (isset($data['profile_picture']) && $data['profile_picture'] instanceof UploadedFile) {
            $data['profile_picture'] = $this->fileUploadService->upload($data['profile_picture']);
        }

        $user = UserFactory::createUser($data);
        return $this->userRepository->create($user);
    }

    /**
     * Update an existing user
     */
    public function updateUser($id, array $data)
    {
        $userId = new UserId($id, $this->userRepository);
        $existingUser = $this->userRepository->find($userId->getValue());

        if (isset($data['profile_picture']) && $data['profile_picture'] instanceof UploadedFile) {
            if ($existingUser->getProfilePicture()) {
                $this->fileUploadService->delete($existingUser->getProfilePicture());
            }

            $data['profile_picture'] = $this->fileUploadService->upload($data['profile_picture']);
        }
        $user = UserFactory::updateUser($data, $existingUser);
        return $this->userRepository->update($id, $user);
    }

    /**
     * Delete a user
     */
    public function deleteUser($id)
    {
        $userId = new UserId($id, $this->userRepository);
        return $this->userRepository->delete($userId->getValue());
    }
}
