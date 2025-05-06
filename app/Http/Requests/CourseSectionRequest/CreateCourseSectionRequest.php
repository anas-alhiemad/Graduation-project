<?php

namespace App\Http\Requests\CourseSectionRequest;

use Illuminate\Foundation\Http\FormRequest;

class CreateCourseSectionRequest extends FormRequest
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
            'name'             => 'required|string',
            'seatsOfNumber'             => 'required|integer',
            'startDate'        => 'required|date',
            'endDate'          => 'required|date|after_or_equal:startDate',
            'days'               => 'required|array',
            'days.*.start_time'  => 'required|date_format:H:i',
            'days.*.end_time'    => 'required|date_format:H:i|after:days.*.start_time',   
            'courseId' => 'required|exists:courses,id',
        ];
    }
}
