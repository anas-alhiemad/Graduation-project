<?php

namespace App\Http\Requests\ForumRequest;

use Illuminate\Foundation\Http\FormRequest;

class CreateQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => 'required|string',
            'course_section_id' => 'required|exists:course_sections,id',
        ];
    }
}
