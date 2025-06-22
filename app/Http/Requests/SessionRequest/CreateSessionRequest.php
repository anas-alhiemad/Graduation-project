<?php

namespace App\Http\Requests\SessionRequest;

use Illuminate\Foundation\Http\FormRequest;

class CreateSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'session_date'      => 'required|date',
            'course_section_id' => 'required|exists:course_sections,id',
        ];
    }
}
