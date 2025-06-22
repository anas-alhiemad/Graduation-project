<?php

namespace App\Http\Requests\GiftRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description'   => 'sometimes|string',
            'date'          => 'sometimes|date',
            'photo'         => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'student_id'    => 'sometimes|exists:students,id',
            'secretary_id'  => 'sometimes|exists:secretaries,id'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $allowedFields = ['description', 'date', 'photo', 'student_id', 'secretary_id'];

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
