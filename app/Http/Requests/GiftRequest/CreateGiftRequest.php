<?php

namespace App\Http\Requests\GiftRequest;

use Illuminate\Foundation\Http\FormRequest;

class CreateGiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description'   => 'required|string',
            'date'          => 'required|date',
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'student_id'    => 'nullable|exists:students,id',
            'secretary_id'  => 'nullable|exists:secretaries,id'
        ];
    }
}
