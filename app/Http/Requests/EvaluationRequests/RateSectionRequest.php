<?php

namespace App\Http\Requests\EvaluationRequests;

use Illuminate\Foundation\Http\FormRequest;

class RateSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'section_id' => 'required|exists:course_sections,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:500',
        ];
    }
}
