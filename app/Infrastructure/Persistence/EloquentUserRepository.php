<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Users\Entities\User as DomainUser;
use App\Domain\Users\Repositories\UserRepositoryInterface;
use App\Models\User;
use App\Domain\Users\ValueObjects\Email;
use App\Domain\Users\ValueObjects\PhoneNumber;
use App\Domain\Users\Enums\Country;
use App\Domain\Users\Enums\Gender;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function exists(int $id): bool
    {
        return User::where('id', $id)->exists();
    }

    public function all(array $filters = []): array
    {
        $columns = [
            'id',
            'name',
            'surname',
            'email',
            'phone',
            'country',
            'gender',
            'password',
            'profile_picture',
            'introduction',
            'created_at'
        ];

        $query = User::select($columns);

        foreach ($filters as $key => $value) {
            if (!empty($value) && in_array($key, $columns)) {
                $query->where($key, 'like', '%' . $value . '%');
            }
        }

        // Get the results
        $users = $query->get();

        // Return the mapped users
        return $users->map(function (User $user) {
            return new DomainUser(
                $user->id,
                $user->name,
                $user->surname,
                new Email($user->email),
                new PhoneNumber($user->phone),
                Country::from($user->country),
                Gender::from($user->gender),
                $user->password,
                $user->profile_picture,
                $user->introduction,
                $user->created_at ? new \DateTime($user->created_at) : null
            );
        })->toArray();
    }


    public function find(int $id)
    {
        $user = User::findOrFail($id);
        return $user ? $user->toDomainEntity() : null;
    }

    public function create(DomainUser $user)
    {
        return DB::transaction(function () use ($user) {
            User::create([
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
                'email' => $user->getEmail()->getValue(),
                'phone' => $user->getPhone()->getValue(),
                'country' => $user->getCountry()->value,
                'gender' => $user->getGender()->value,
                'password' => Hash::make($user->getPassword()), // Hashing password here for better control
                'profile_picture' => $user->getProfilePicture(),
                'introduction' => $user->getIntroduction(),
            ]);

            return $user;
        });
    }

    public function update(int $id, DomainUser $user)
    {
        $dbUser = User::findOrFail($id);
        $dbUser->update([
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'email' => $user->getEmail()->getValue(),
            'phone' => $user->getPhone()->getValue(),
            'country' => $user->getCountry(),
            'gender' => $user->getGender(),
            'password' => Hash::make($user->getPassword()), // Hashing password here for better control
            'profile_picture' => $user->getProfilePicture(),
            'introduction' => $user->getIntroduction(),
        ]);
        return $user;
    }


    public function delete(int $id): void
    {
        $user = User::find($id);
        $user->delete();
    }
}
