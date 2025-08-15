<?php

namespace App\Services\EvaluationServices;

use App\Models\Student;
use App\Models\Trainer;
use App\Models\CourseSection;
use App\Models\TrainerRating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\TrainerRatingRepository;
class TrainerRatingService
{
    protected $trainerRatingRepository;

    public function __construct(TrainerRatingRepository $trainerRatingRepository)
    {
        $this->trainerRatingRepository = $trainerRatingRepository;
    }

    public function getTrainersStatistics($startDate = null, $endDate = null, $limit = null)
    {
        $stats = $this->trainerRatingRepository->getTrainerRatingsStatistics($startDate, $endDate, $limit);

        return $stats->map(function ($row) {
            return [
                'trainer_id'     => $row->trainer_id,
                'trainer_name'   => $row->trainer->name ?? 'N/A',
                'average_rating' => round($row->average_rating, 2),
                'total_ratings'  => $row->total_ratings,
            ];
        });
    }
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
