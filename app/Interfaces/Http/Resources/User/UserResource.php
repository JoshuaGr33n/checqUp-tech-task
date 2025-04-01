<?php

namespace App\Interfaces\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Users\Entities\User;

/**
 * @OA\Schema(
 * schema="UserResource",
 * title="User Resource",
 * @OA\Property(property="id", type="integer", example=1),
 * @OA\Property(property="name", type="string", example="John Doe"),
 * @OA\Property(property="surname", type="string", example="Doe"),
 * @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 * @OA\Property(property="phone", type="string", example="+1234567890"),
 * @OA\Property(property="country", type="string", example="USA"),
 * @OA\Property(property="gender", type="string", example="male"),
 * @OA\Property(property="profile_picture", type="string", example="profiles/john.jpg"),
 * @OA\Property(property="introduction", type="string", example="Hello world")
 * )
 */
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var User $this */
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'surname' => $this->getSurname(),
            'email' => $this->getEmail()->getValue(),
            'phone' => $this->getPhone()->getValue(),
            'country' => $this->getCountry()->value,
            'gender' => $this->getGender()->value,
            'profile_picture' => $this->getProfilePicture(),
            'introduction' => $this->getIntroduction(),
            'created_at' => $this->getCreatedAt()?->format('Y-m-d H:i:s')
        ];
    }
}