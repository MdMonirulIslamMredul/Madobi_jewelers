<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role_id' => 'required|numeric',
            'name' => 'nullable|string|max:255|',
            'email' => 'nullable|string|email|max:255',
            'password' => ['nullable', 'string', Password::min(4)->mixedCase()],
            // 'is_active' => 'nullable'
        ];
    }
}
