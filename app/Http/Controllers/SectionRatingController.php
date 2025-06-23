<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluationRequests\RateSectionRequest;
use App\Services\EvaluationServices\SectionRatingService;
use Illuminate\Http\JsonResponse;

class SectionRatingController extends Controller
{
    protected $sectionRatingService;

    public function __construct(SectionRatingService $sectionRatingService)
    {
        $this->sectionRatingService = $sectionRatingService;
    }

    public function rateSection(RateSectionRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $rating = $this->sectionRatingService->rateSection(
                $data['section_id'],
                $data['rating'],
                $data['comment'] ?? null
            );

            return response()->json([
                'message' => 'Section rating submitted successfully',
                'data'    => $rating,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getSectionRatings($sectionId): JsonResponse
    {
        try {
            $ratings = $this->sectionRatingService->getSectionRatings($sectionId);
            $averageRating = $this->sectionRatingService->getAverageRating($sectionId);

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
