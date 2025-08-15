<?php

namespace App\Http\Requests\EvaluationRequests;

use Illuminate\Foundation\Http\FormRequest;

class TrainerStatisticsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'limit'      => 'nullable|integer|min:1'
        ];
    }

    public function authorize()
    {
        return true; 
    }
}
