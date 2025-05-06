<?php

namespace App\Http\Requests\CourseSectionRequest;

use Illuminate\Foundation\Http\FormRequest;

class SectionTrainertRequest extends FormRequest
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
            'course_section_id' => 'required|exists:course_sections,id',
            'trainer_id' => 'required|exists:trainers,id',
        ];
    }
}
