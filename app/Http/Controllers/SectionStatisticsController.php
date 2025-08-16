<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\EvaluationServices\SectionRatingService;
use Illuminate\Http\Request;

class SectionStatisticsController extends Controller
{
    protected $sectionRatingService;

    public function __construct(SectionRatingService $sectionRatingService)
    {
        $this->sectionRatingService = $sectionRatingService;
    }

    // API للداشبورد: إحصائيات كل الأقسام
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $limit     = $request->input('limit');

        $data = $this->sectionRatingService->getSectionsStatistics($startDate, $endDate, $limit);

        return response()->json([
            'message' => 'Section ratings statistics',
            'data'    => $data
        ]);
    }
}
