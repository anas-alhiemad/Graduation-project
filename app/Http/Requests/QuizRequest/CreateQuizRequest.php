<?php

namespace App\Http\Requests\QuizRequest;

use Illuminate\Foundation\Http\FormRequest;

class CreateQuizRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
                'title' => 'required|string',
                'course_section_id' => 'required|exists:course_sections,id',
                'questions' => 'required|array|min:1',

                'questions.*.question' => 'required|string',
                'questions.*.options' => 'required|array|min:2',
                'questions.*.options.*.option' => 'required|string',
                'questions.*.options.*.is_correct' => 'required|boolean',

        ];
    }
}
