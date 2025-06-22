<?php

namespace App\Http\Requests\ReportRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
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
            'file' => 'sometimes|file|max:10240'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $allowedFields = ['name', 'description', 'file'];

            if (!$this->hasAnyField($allowedFields)) {
                $validator->errors()->add('fields', 'You must provide at least one field to update.');
            }

            foreach (array_keys($this->all()) as $inputField) {
                if (!in_array($inputField, $allowedFields)) {
                    $validator->errors()->add($inputField, 'Modification of this field is not allowed.');
                }
            }
        });
    }

    protected function hasAnyField(array $fields): bool
    {
        foreach ($fields as $field) {
            if ($this->has($field)) {
                return true;
            }
        }
        return false;
    }
}
