<?php
namespace App\Interfaces\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Domain\Users\Enums\Country;
use App\Domain\Users\Enums\Gender;


/**
 * @OA\Schema(
 * schema="UpdateUserRequest",
 * title="Update User Request",
 * @OA\Property(property="name", type="string", example="Jane Doe"),
 * @OA\Property(property="surname", type="string", example="Doe"),
 * @OA\Property(property="email", type="string", format="email", example="jane@example.com"),
 * @OA\Property(property="phone", type="string", example="+1234567890"),
 * @OA\Property(property="country", type="string", example="USA"),
 * @OA\Property(property="gender", type="string", example="male"),
 * @OA\Property(property="profile_picture", type="string", example="profiles/jane.jpg"),
 * @OA\Property(property="introduction", type="string", example="Hello world"),
 * @OA\Property(property="password", type="string", example="password"),
 *  @OA\Property(property="password_confirmation", type="string", example="password123")
 * )
 */
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'surname' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $this->route('user'), // Allow updating with the same email
            'phone' => [
                'sometimes',
                'required',
                'string',
                'min:10',
                'max:15',
                'unique:users,phone,' . $this->route('user'), // Allow updating with the same phone number
                'regex:/^\+?[0-9]{7,15}$/',
            ],
            'country' => [
                'sometimes',
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Country::isValid($value)) {
                        $fail("Invalid country selected.");
                    }
                },
            ],
            'gender' => [
                'sometimes',
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Gender::isValid($value)) {
                        $fail("Invalid gender selected.");
                    }
                },
            ],
            'password' => 'sometimes|required|string|min:8|confirmed', 
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'introduction' => 'nullable|string',
        ];
    }
}
