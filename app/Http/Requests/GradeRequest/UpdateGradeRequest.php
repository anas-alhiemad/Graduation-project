<?php
namespace App\Http\Requests\GradeRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGradeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'grade' => 'required|numeric|min:0|max:100',
        ];
    }
}
