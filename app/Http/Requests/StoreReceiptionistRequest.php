<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReceiptionistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

//
//               return in_array(auth()->user()->role, ['admin', 'manager']) || $this->isOwner();

        return in_array(auth()->user()->role, ['admin', 'manager']) ;

//                return in_array(auth()->user()->role, ['admin']) || $this->isOwner() || $this->isManagerOwner();

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
            'avatar_img' => ['required','image','mimes:jpg,png,jpeg','max:2048'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'mobile' => ['required', 'string', 'unique:users,mobile'],
            'country' => ['required', 'string'],
        ];

    }

    /**
     *
     * @return bool
     */
    private function isOwner(): bool
    {
        $user = $this->route('user');

        return $user && $user->created_by == auth()->id();
    }

    /**
     *
     * @return bool
     */
    private function isManagerOwner(): bool
    {
        $user = $this->route('user');

        return $user && $user->manager_id == auth()->id();
    }

}
