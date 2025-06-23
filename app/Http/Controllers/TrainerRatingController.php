<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluationRequests\RateTrainerRequest;
use App\Services\EvaluationServices\TrainerRatingService;
use Illuminate\Http\JsonResponse;

class TrainerRatingController extends Controller
{
    protected $trainerRatingService;

    public function __construct(TrainerRatingService $trainerRatingService)
    {
        $this->trainerRatingService = $trainerRatingService;
    }

    public function rateTrainer(RateTrainerRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $rating = $this->trainerRatingService->rateTrainer(
                $data['trainer_id'],
                $data['section_id'],
                $data['rating'],
                $data['comment'] ?? null
            );

            return response()->json([
                'message' => 'Trainer rating submitted successfully',
                'data'    => $rating,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getTrainerRatings($trainerId, $sectionId): JsonResponse
    {
        try {
            $ratings = $this->trainerRatingService->getTrainerRatings($trainerId, $sectionId);
            $averageRating = $this->trainerRatingService->getAverageRating($trainerId, $sectionId);

            return response()->json([
                'ratings' => $ratings,
                'average_rating' => $averageRating,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
