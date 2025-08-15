<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Services\EvaluationServices\TrainerRatingService;
use App\Http\Requests\EvaluationRequests\TrainerStatisticsRequest;


class TrainerStatisticsController extends Controller
{
    protected $trainerRatingService;

    public function __construct(TrainerRatingService $trainerRatingService)
    {
        $this->trainerRatingService = $trainerRatingService;
    }

    public function index(TrainerStatisticsRequest $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $limit     = $request->input('limit');

        $data = $this->trainerRatingService->getTrainersStatistics($startDate, $endDate, $limit);

        return response()->json([
            'message' => 'Trainer ratings statistics',
            'data'    => $data
        ]);
    }
}
