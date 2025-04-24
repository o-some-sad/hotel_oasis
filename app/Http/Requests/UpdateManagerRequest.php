<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateManagerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     // Example authorization check, allowing only admin users to update.
    //     return in_array(auth()->user()->role, ['admin']) || $this->isOwner();
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $manager = $this->route('manager')->id;
        return [
            'name' => ['string', 'min:5'],
            'password' => ['nullable','string', 'min:6'],
            'email' => ['string', 'email', Rule::unique('users', 'email')->ignore($manager)],
            'national_id' => ['string', Rule::unique('users', 'national_id')->ignore($manager)],
            'avatar_img' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
            'gender' => [Rule::in(['male', 'female'])],
            'mobile' => ['string', Rule::unique('users', 'mobile')->ignore($manager)],
            'country' => ['string'],
        ];
        
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array
     */
    public function messages(): array
    {
        return [

            'name.min' => 'The name must be at least 5 characters.',
            'password.min' => 'The password must be at least 6 characters.',
            'national_id.unique' => 'The national ID has already been taken.',
            'email.unique' => 'The email has already been taken.',
            'email.email' => 'The email must be a valid email address.',
            'email.string' => 'The email must be a string.',
            'name.string' => 'The name must be a string.',
            'mobile.unique' => 'The mobile number has already been taken.',
            'avatar_img.image' => 'The avatar image must be an image.',
            'avatar_img.mimes' => 'The avatar image must be a file of type: jpg, png, jpeg.',
            'avatar_img.max' => 'The avatar image may not be greater than 2048 kilobytes.',
        ];
    }

    /**
     * Custom method to check if the user is the owner of the manager.
     *
     * @return bool
     */
    // private function isOwner(): bool
    // {
    //     // Replace with your own logic for checking if the authenticated user is the manager's owner.
    //     return auth()->user()->id === $this->route('manager')->user_id;
    // }
}
