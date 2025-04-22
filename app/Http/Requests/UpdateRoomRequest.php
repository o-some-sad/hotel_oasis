<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    /**
     */
    public function authorize(): bool
    {
        $id = $this->route('rooms');

        return in_array(auth()->user()->role, ['admin']) || auth()->id() === User::find($id)->created_by;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'number' => ['sometimes','required', 'string','min:4'],
            'capacity' => ['sometimes','required', 'string'],
            'price' => ['sometimes','required', 'string', 'min:6'],
            'reserved' => ['sometimes','required', 'string'],
        ];
    }
}
