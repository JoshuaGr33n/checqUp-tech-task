<?php

namespace App\Interfaces\Http\Controllers\Users;

use App\Application\Users\Services\UserService;
use App\Interfaces\Http\Requests\User\CreateUserRequest;
use App\Interfaces\Http\Requests\User\UpdateUserRequest;
use App\Interfaces\Http\Resources\User\UserResource;
use App\Interfaces\Http\Resources\User\UserCollection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Interfaces\Http\Controllers\BaseController;


class UserController extends BaseController
{
    private UserService $userService;
   

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
       
    }

    /**
     * @OA\Get(
     * path="/api/v1/users",
     * summary="List all users",
     * tags={"Users"},
     * @OA\Parameter(
     * name="name",
     * in="query",
     * description="Filter by user name",
     * required=false,
     * @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     * name="country",
     * in="query",
     * description="Filter by country",
     * required=false,
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=200,
     * description="List of users",
     * @OA\JsonContent(ref="#/components/schemas/UserCollection")
     * )
     * )
     */
    public function index(Request $request): UserCollection
    {
        $filters = $request->only(['name', 'country']);
        $users = $this->userService->getAllUsers($filters);
        return new UserCollection($users);
    }

    /**
     * @OA\Post(
     * path="/api/v1/users",
     * summary="Create a new user",
     * tags={"Users"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/CreateUserRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="User created successfully",
     * @OA\JsonContent(ref="#/components/schemas/UserResource")
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation errors",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Validation failed"),
     * @OA\Property(
     * property="errors",
     * type="object",
     * @OA\Property(
     * property="field",
     * type="array",
     * @OA\Items(type="string", example="The field is required.")
     * )
     * )
     * )
     * )
     * )
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        // dd($request->all()); 
        $user = $this->userService->createUser($request->validated());
        return response()->json([
            'message' => 'User created successfully!',
            'data' => new UserResource($user),
        ], 201);
    }

    /**
     * @OA\Get(
     * path="/api/v1/users/{id}",
     * summary="Get user details",
     * tags={"Users"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="User ID",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="User details",
     * @OA\JsonContent(ref="#/components/schemas/UserResource")
     * ),
     * @OA\Response(
     * response=404,
     * description="User not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="User not found")
     * )
     * )
     * )
     */
    public function show($id): UserResource
    {
        $user = $this->userService->getUserById($id);
        return new UserResource($user);
    }

    /**
     * @OA\Put(
     * path="/api/v1/users/{id}",
     * summary="Update user details",
     * tags={"Users"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="User ID",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/UpdateUserRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="User updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/UserResource")
     * ),
     * @OA\Response(
     * response=404,
     * description="User not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="User not found")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation errors",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Validation failed"),
     * @OA\Property(
     * property="errors",
     * type="object",
     * @OA\Property(
     * property="field",
     * type="array",
     * @OA\Items(type="string", example="The field is required.")
     * )
     * )
     * )
     * )
     * )
     */
    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        $user = $this->userService->updateUser($id, $request->all());
        return response()->json([
            'message' => 'User updated successfully!',
            'data' => new UserResource($user),
        ], 200);
    }

    /**
     * @OA\Delete(
     * path="/api/v1/users/{id}",
     * summary="Delete a user",
     * tags={"Users"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="User ID",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="User deleted successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="User deleted successfully")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="User not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="User not found")
     * )
     * )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $this->userService->deleteUser($id);
        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }
}
