<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required','string','max:100'],
            'last_name'  => ['required','string','max:100'],
            'phone'      => ['nullable','string','max:20'],
            'email'      => ['required','string','email','max:255','unique:users,email,'.$this->user()->id],
        ];
    }
}
