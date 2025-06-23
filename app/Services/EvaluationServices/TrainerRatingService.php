<?php

namespace App\Services\EvaluationServices;

use App\Models\Student;
use App\Models\Trainer;
use App\Models\CourseSection;
use App\Models\TrainerRating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TrainerRatingService
{
    public function rateTrainer($trainerId, $sectionId, $rating, $comment = null)
    {
        $student = Auth::user();

        $isInSection = $student->sections()
            ->where('course_sections.id', $sectionId)
            ->exists();

        if (!$isInSection) {
            throw new \Exception('You are not enrolled in this section.');
        }

        $isTrainerInSection = CourseSection::find($sectionId)
            ->trainers()
            ->where('trainers.id', $trainerId)
            ->exists();

        if (!$isTrainerInSection) {
            throw new \Exception('This trainer is not assigned to this section.');
        }

        if ($rating < 1 || $rating > 5) {
            throw new \Exception('Rating must be between 1 and 5.');
        }

        return DB::transaction(function () use ($student, $trainerId, $sectionId, $rating, $comment) {
            return TrainerRating::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'trainer_id' => $trainerId,
                    'course_section_id' => $sectionId,
                ],
                [
                    'rating' => $rating,
                    'comment' => $comment,
                ]
            );
        });
    }

    public function getTrainerRatings($trainerId, $sectionId)
    {
        return TrainerRating::with(['student:id,name,photo'])
            ->where('trainer_id', $trainerId)
            ->where('course_section_id', $sectionId)
            ->get();
    }

    public function getAverageRating($trainerId, $sectionId)
    {
        return TrainerRating::where('trainer_id', $trainerId)
            ->where('course_section_id', $sectionId)
            ->avg('rating');
    }
}
