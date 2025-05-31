<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CourseSectionServices\TrainerRatingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainerRatingController extends Controller
{
    protected $trainerRatingService;

    public function __construct(TrainerRatingService $trainerRatingService)
    {
        $this->trainerRatingService = $trainerRatingService;
    }

    public function rateTrainer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trainer_id' => 'required|exists:trainers,id',
            'section_id' => 'required|exists:course_sections,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $rating = $this->trainerRatingService->rateTrainer(
                $request->trainer_id,
                $request->section_id,
                $request->rating,
                $request->comment
            );

            return response()->json([
                'message' => 'Rating submitted successfully',
                'data' => $rating
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getTrainerRatings($trainerId, $sectionId)
    {
        try {
            $ratings = $this->trainerRatingService->getTrainerRatings($trainerId, $sectionId);
            $averageRating = $this->trainerRatingService->getAverageRating($trainerId, $sectionId);

            return response()->json([
                'ratings' => $ratings,
                'average_rating' => $averageRating
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
} 