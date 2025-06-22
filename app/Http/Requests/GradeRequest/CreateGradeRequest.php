<?php
namespace App\Http\Requests\GradeRequest;

use Illuminate\Foundation\Http\FormRequest;

class CreateGradeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id' => 'required|exists:students,id',
            'exam_id'    => 'required|exists:exams,id',
            'grade'      => 'required|numeric|min:0|max:100',
        ];
    }
}
