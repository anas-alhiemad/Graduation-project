<?php

namespace App\Http\Requests\ReportRequest;

use Illuminate\Foundation\Http\FormRequest;

class CreateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'required|file|max:10240'
        ];
    }
}

