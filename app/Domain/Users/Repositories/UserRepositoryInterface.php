<?php

namespace App\Domain\Users\Repositories;

use App\Domain\Users\Entities\User;

interface UserRepositoryInterface
{
    public function all(array $filters = []): array;
    public function find(int $id);
    public function create(User $user);
    public function update(int $id, User $user);
    public function delete(int $id) : void;
    public function exists(int $id): bool;
}
