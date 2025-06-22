<?php

namespace App\Http\Requests\ExamRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExamRequest extends FormRequest
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
        ];
    }
}
