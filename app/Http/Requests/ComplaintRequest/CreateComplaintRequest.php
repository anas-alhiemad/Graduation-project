<?php

namespace App\Http\Requests\ComplaintRequest;

use Illuminate\Foundation\Http\FormRequest;

class CreateComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'description' => 'required|string|min:10',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ];
    }
}
