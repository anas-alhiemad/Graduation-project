<?php

namespace App\Http\Requests\EmployeeRequest;

use Illuminate\Foundation\Http\FormRequest;

class AddEmployeeRequest extends FormRequest
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
            'name' => 'required|string|between:2,30',
            'email' => 'required|string|email|max:100|unique:trainers',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|regex:/^[0-9+\-\s()]*$/|min:10|max:12',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'birthday' => 'required|date|before:today|after:1980-01-01',
            'gender'=> 'required|string',
            'role'=> 'required|string',
        ];
    }
}
