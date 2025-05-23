<?php

namespace App\Http\Requests\FileRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFileRequest extends FormRequest
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
            'course_section_id' => 'integer|exists:course_sections,id',
            'file_Id'           => 'integer|exists:section__files,id',
            'file'             => 'required|file'
        ];
    }
}
