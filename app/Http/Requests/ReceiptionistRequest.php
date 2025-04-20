<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReceiptionistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'avatar_image' => ['nullable','image','mimes:jpg,png','max:2048'],
            'gender' => ['required', Rule::in(['Male', 'Female'])],
            'created_by' => ['required', 'integer', 'exists:users,id'],
            'approved_by' => ['required', 'integer', 'exists:users,id'],
            'mobile' => ['required', 'string', 'unique:users,mobile'],
            'country' => ['required', 'string'],

        ];
    }
}
