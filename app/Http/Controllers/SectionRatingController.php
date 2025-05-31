<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CourseSectionServices\SectionRatingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SectionRatingController extends Controller
{
    protected $sectionRatingService;

    public function __construct(SectionRatingService $sectionRatingService)
    {
        $this->sectionRatingService = $sectionRatingService;
    }

    public function rateSection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_id' => 'required|exists:course_sections,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $rating = $this->sectionRatingService->rateSection(
                $request->section_id,
                $request->rating,
                $request->comment
            );

            return response()->json([
                'message' => 'Section rating submitted successfully',
                'data' => $rating
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getSectionRatings($sectionId)
    {
        try {
            $ratings = $this->sectionRatingService->getSectionRatings($sectionId);
            $averageRating = $this->sectionRatingService->getAverageRating($sectionId);

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