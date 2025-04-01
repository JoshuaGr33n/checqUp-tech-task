<?php

namespace App\Interfaces\Http\Resources\User;

use Illuminate\Http\Resources\Json\ResourceCollection;


/**
 * @OA\Schema(
 * schema="UserCollection",
 * title="User Collection",
 * @OA\Property(
 * property="data",
 * type="array",
 * @OA\Items(ref="#/components/schemas/UserResource")
 * ),
 * @OA\Property(
 * property="meta",
 * type="object",
 * @OA\Property(property="total", type="integer", example=10)
 * )
 * )
 */
class UserCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => UserResource::collection($this->collection),
            'meta' => [
                'total' => $this->collection->count(),
            ]
        ];
    }
}