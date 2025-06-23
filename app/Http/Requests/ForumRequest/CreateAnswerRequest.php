<?php

namespace App\Http\Requests\ForumRequest;

use Illuminate\Foundation\Http\FormRequest;

class CreateAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => 'required|string',
            'question_id' => 'required|exists:questions,id',
            'course_section_id' => 'required|exists:course_sections,id',
        ];
    }
}
