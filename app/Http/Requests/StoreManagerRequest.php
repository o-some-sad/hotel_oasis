<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreManagerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Adjust as needed (e.g., only admins can create managers)
        return auth()->check(); // or: return auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string','min:5'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'national_id' => ['required', 'string', 'unique:users,national_id'],
            'avatar_img' => ['nullable','image','mimes:jpg,png,jpeg','max:2048'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'mobile' => ['required', 'string', 'unique:users,mobile'],
            'country' => ['required', 'string'],
        ];

    }
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'password.required' => 'The password field is required.',
            'national_id.required' => 'The national ID field is required.',
            'gender.required' => 'Gender is required.',
            'mobile.required' => 'The mobile field is required.',
            'country.required' => 'The country field is required.',
            'name.min' => 'The name must be at least 5 characters.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'password.min' => 'The password must be at least 6 characters.',
            'national_id.unique' => 'The national ID has already been taken.',
            'avatar_img.image' => 'The avatar image must be an image.',
            'avatar_img.mimes' => 'The avatar image must be a file of type: jpg, png, jpeg.',
            'avatar_img.max' => 'The avatar image may not be greater than 2048 kilobytes.',
        ];
    }
    /**
     *
     * @return bool
     */
    private function isAuthenticatedUserAdmin(): bool
    {
        return auth()->user()->role === 'admin';
    }


}
