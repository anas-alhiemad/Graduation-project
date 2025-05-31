<?php

namespace App\Services\CourseSectionServices;

use App\Models\Student;
use App\Models\CourseSection;
use App\Models\SectionRating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SectionRatingService
{
    public function rateSection($sectionId, $rating, $comment = null)
    {
        $student = Auth::user();
        
        // Validate that the student is in the section
        $isInSection = $student->sections()
            ->where('course_sections.id', $sectionId)
            ->exists();

        if (!$isInSection) {
            throw new \Exception('You are not enrolled in this section.');
        }

        // Validate rating range
        if ($rating < 1 || $rating > 5) {
            throw new \Exception('Rating must be between 1 and 5.');
        }

        return DB::transaction(function () use ($student, $sectionId, $rating, $comment) {
            return SectionRating::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'course_section_id' => $sectionId,
                ],
                [
                    'rating' => $rating,
                    'comment' => $comment,
                ]
            );
        });
    }

    public function getSectionRatings($sectionId)
    {
        return SectionRating::with(['student:id,name,photo'])
            ->where('course_section_id', $sectionId)
            ->get();
    }

    public function getAverageRating($sectionId)
    {
        return SectionRating::where('course_section_id', $sectionId)
            ->avg('rating');
    }
} 