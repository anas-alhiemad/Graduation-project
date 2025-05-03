<?php

namespace App\Http\Requests\TrainerRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainerRequest extends FormRequest
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
            'name' => 'nullable|string|between:2,30',
            'phone' => 'nullable|string|regex:/^[0-9+\-\s()]*$/|min:10|max:12',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'birthday' => 'nullable|date|before:today|after:1980-01-01',
            'gender' => 'nullable|string',
        ];
    }
    
    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $allowedFields = ['name', 'phone', 'photo', 'birthday', 'gender'];

          
            if (!$this->hasAny($allowedFields)) {
                $validator->errors()->add('fields', 'You must fill at least one field to update.');
            }

          
            foreach (array_keys($this->all()) as $inputField) {
                if (!in_array($inputField, $allowedFields)) {
                    $validator->errors()->add($inputField, 'Modification of this field is not allowed.');
                }
            }
        });
    }
}


