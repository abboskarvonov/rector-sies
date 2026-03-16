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
            'full_name'        => ['required', 'string', 'min:3', 'max:100'],
            'passport_number'  => ['required', 'string', 'regex:/^[A-Z]{2}[0-9]{7}$/'],
            'phone'            => ['required', 'string', 'regex:/^\+998[0-9]{9}$/'],
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
            'category_id.required' => 'Murojaat turini tanlang.',
            'category_id.exists'   => 'Tanlangan kategoriya mavjud emas.',

            'full_name.required' => 'To\'liq ism-familiya majburiy.',
            'full_name.min'      => 'Ism-familiya kamida 3 ta belgidan iborat bo\'lishi kerak.',
            'full_name.max'      => 'Ism-familiya 100 ta belgidan oshmasligi kerak.',

            'passport_number.required' => 'Pasport seriyasi va raqami majburiy.',
            'passport_number.regex'    => 'Pasport formati noto\'g\'ri. Namuna: AA1234567',

            'phone.required' => 'Telefon raqami majburiy.',
            'phone.regex'    => 'Telefon raqami noto\'g\'ri formatda. Namuna: +998901234567',

            'email.email' => 'Elektron pochta manzili noto\'g\'ri formatda.',
            'email.max'   => 'Elektron pochta 255 ta belgidan oshmasligi kerak.',

            'subject.required' => 'Murojaat mavzusi majburiy.',
            'subject.min'      => 'Mavzu kamida 5 ta belgidan iborat bo\'lishi kerak.',
            'subject.max'      => 'Mavzu 200 ta belgidan oshmasligi kerak.',

            'body.required' => 'Murojaat matni majburiy.',
            'body.min'      => 'Murojaat matni kamida 20 ta belgidan iborat bo\'lishi kerak.',
            'body.max'      => 'Murojaat matni 2000 ta belgidan oshmasligi kerak.',

            'files.max'     => 'Ko\'pi bilan 5 ta fayl yuklash mumkin.',
            'files.*.mimes' => 'Ruxsat etilgan fayl turlari: PDF, JPG, PNG, DOCX.',
            'files.*.max'   => 'Har bir fayl 5 MB dan oshmasligi kerak.',
        ];
    }
}
