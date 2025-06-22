<?php

namespace App\Http\Requests\DepartmentRequest;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{

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
            'photo' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $allowedFields = ['name', 'photo'];

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