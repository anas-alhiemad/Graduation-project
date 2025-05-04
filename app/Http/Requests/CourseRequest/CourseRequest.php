<?php

namespace App\Http\Requests\CourseRequest;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'photo' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'state' => 'sometimes|required|in:not_start,in_progress,finished',
            'department_id' => 'sometimes|required|exists:departments,id'
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $allowedFields = ['name', 'description', 'photo', 'state', 'department_id'];
            
            // Debug the incoming data
            \Log::info('Course Update Request Data:', [
                'all_data' => $this->all(),
                'has_name' => $this->has('name'),
                'name_value' => $this->input('name'),
                'allowed_fields' => $allowedFields
            ]);

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