<?php

namespace App\Http\Requests\CourseSectionRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseSectionRequest extends FormRequest
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
            'name'              => 'sometimes|required|string',
            'seatsOfNumber'     => 'sometimes|required|integer',
            'startDate'         => 'sometimes|required|date',
            'endDate'           => 'sometimes|required|date|after_or_equal:startDate',
            'state'             => 'sometimes|required|in:pending,in_progress,finished', 
            'days'              => 'sometimes|required|array',
            'days.*.start_time' => 'required_with:days|date_format:H:i',
            'days.*.end_time'   => 'required_with:days|date_format:H:i|after:days.*.start_time',  
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $allowedFields = [
                'name', 'seatsOfNumber', 'startDate', 'endDate', 'state', 'days'
            ];

            if (!$this->hasAny($allowedFields)) {
                $validator->errors()->add('fields', 'You must provide at least one field to update.');
            }

            foreach (array_keys($this->all()) as $inputField) {
                if (!in_array($inputField, $allowedFields)) {
                    $validator->errors()->add($inputField, 'Modification of this field is not allowed.');
                }
            }
        });
    }
}
