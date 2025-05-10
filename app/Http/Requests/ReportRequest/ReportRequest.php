<?php

namespace App\Http\Requests\ReportRequest;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'file' => 'sometimes|file|max:10240' // 10MB max
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $allowedFields = ['name', 'description', 'file'];
            
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