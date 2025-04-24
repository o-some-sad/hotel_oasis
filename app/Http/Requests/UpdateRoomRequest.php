<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
{
    /**
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
        try {
            $id = optional($this->route('room'))->id ?? $this->route('room');
            return [
                'number' => ['sometimes', 'required', 'integer', Rule::unique('rooms', 'number')->ignore($id)],
                'capacity' => ['sometimes', 'required', 'integer', 'min:1'],
                'price' => ['sometimes', 'required', 'numeric', 'min:0'],
                'reserved' => ['sometimes', 'required', 'boolean'],
                'floor_id' => ['sometimes', 'required', 'integer', 'exists:floors,id'],
            ];

        }
        catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
