<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array(auth()->user()->role, ['admin', 'manager']) ;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'number' => ['required', 'digits:4', 'unique:rooms,number'],
            'capacity' => ['required', 'integer'],
            'price' => ['required', 'numeric'],
            'floor_id' => ['required', 'exists:floors,id'],
        ];
    }
}
