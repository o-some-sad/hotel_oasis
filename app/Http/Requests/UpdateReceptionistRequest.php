<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReceptionistRequest extends FormRequest
{
    public function authorize(): bool
    {
        $id = $this->route('receptionist');

        return in_array(auth()->user()->role, ['admin', 'manager']) || auth()->id() === User::find($id)->created_by;
    }


    public function rules(): array
    {
        $id = $this->route('receptionist');

        return [
            'name' => ['sometimes', 'string', 'min:5'],
            'email' => ['sometimes', 'required', 'string', 'email', Rule::unique('users', 'email')->ignore($id)],
            'avatar_img' => ['nullable','image', 'mimes:jpg,png,jpeg', 'max:2048'],
            'gender' => ['sometimes', 'required', Rule::in(['male', 'female'])],
            'mobile' => ['sometimes', 'required', 'string', Rule::unique('users', 'mobile')->ignore($id)],
            'country' => ['sometimes', 'required', 'string'],
            'banned_at'=> ['sometimes', 'nullable', 'date'],

        ];
    }

}
