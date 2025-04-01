<?php

namespace App\Interfaces\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Domain\Users\Enums\Country;
use App\Domain\Users\Enums\Gender;

/**
 * @OA\Schema(
 * schema="CreateUserRequest",
 * title="Create User Request",
 * @OA\Property(property="name", type="string", example="John Doe"),
 * @OA\Property(property="surname", type="string", example="Doe"),
 * @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 * @OA\Property(property="phone", type="string", example="+1234567890"),
 * @OA\Property(property="country", type="string", example="USA"),
 * @OA\Property(property="gender", type="string", example="male"),
 * @OA\Property(property="password", type="string", example="password123"),
 *  @OA\Property(property="password_confirmation", type="string", example="password123")
 * )
 */
class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => [
                'required',
                'string',
                'min:10',
                'max:15',
                'unique:users,phone',
                'regex:/^\+?[0-9]{7,15}$/',
            ],
            'country' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Country::isValid($value)) {
                        $fail("Invalid country selected.");
                    }
                },
            ],
            'gender' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Gender::isValid($value)) {
                        $fail("Invalid gender selected.");
                    }
                },
            ],
            'password' => 'required|string|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'introduction' => 'nullable|string',
        ];
    }
}