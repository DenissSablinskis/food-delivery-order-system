<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->route('user'))],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($this->route('user'))],
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s\'-]+$/u'],
            'surname' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s\'-]+$/u'],
            'role' => ['required', 'string', Rule::in(['admin', 'courier', 'operator', 'customer'])],
        ];
    }
}
