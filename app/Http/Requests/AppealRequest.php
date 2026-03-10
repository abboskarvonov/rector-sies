<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'full_name'   => ['required', 'string', 'min:3', 'max:100'],
            'phone'       => ['required', 'string', 'regex:/^\+998[0-9]{9}$/'],
            'email'       => ['nullable', 'email:rfc', 'max:255'],
            'subject'     => ['required', 'string', 'min:5', 'max:200'],
            'body'        => ['required', 'string', 'min:20', 'max:2000'],
            'files'       => ['nullable', 'array', 'max:5'],
            'files.*'     => ['file', 'mimes:pdf,jpg,jpeg,png,docx', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex'    => 'Phone must be in Uzbekistan format: +998XXXXXXXXX.',
            'files.max'      => 'You may upload at most 5 files.',
            'files.*.mimes'  => 'Allowed file types: pdf, jpg, jpeg, png, docx.',
            'files.*.max'    => 'Each file must not exceed 5 MB.',
        ];
    }
}
