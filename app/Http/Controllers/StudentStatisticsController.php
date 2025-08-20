<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StudentServices\StudentStatisticsService;

class StudentStatisticsController extends Controller
{
    protected $studentStatisticsService;

    public function __construct(StudentStatisticsService $studentStatisticsService)
    {
        $this->studentStatisticsService = $studentStatisticsService;
    }

    
    public function monthly(Request $request)
    {
        $year = $request->input('year'); // OPTINAL
        $data = $this->studentStatisticsService->getMonthlyRegistrations($year);

        return response()->json([
            'message' => 'Monthly student registrations',
            'data' => $data
        ]);
    }


    public function yearly()
    {
        $data = $this->studentStatisticsService->getYearlyRegistrations();

        return response()->json([
            'message' => 'Yearly student registrations',
            'data' => $data
        ]);
    }
}
