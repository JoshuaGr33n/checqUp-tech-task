<?php 
namespace App\Domain\Users\ValueObjects;

use App\Domain\Users\Exceptions\InvalidUserIdException;
use App\Domain\Users\Exceptions\UserNotFoundException;
use App\Domain\Users\Repositories\UserRepositoryInterface;

class UserId
{
    private int $value;

    public function __construct($id, UserRepositoryInterface $repository)
    {
        // Format validation
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidUserIdException();
        }

        $this->value = (int)$id;

        // Existence check
        if (!$repository->exists($this->value)) {
            throw new UserNotFoundException();
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }
}