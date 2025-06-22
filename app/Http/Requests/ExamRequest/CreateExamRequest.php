<?php

namespace App\Http\Requests\ExamRequest;

use Illuminate\Foundation\Http\FormRequest;

class CreateExamRequest extends FormRequest
{
    public function authorize()
    {
        
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'exam_date' => 'required|date',
            'course_section_id' => 'required|exists:course_sections,id',
        ];
    }
}

