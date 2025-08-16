<?php

namespace App\Repositories;

use App\Models\TrainerRating;
use Illuminate\Support\Facades\DB;

class TrainerRatingRepository
{
    protected $model;

    public function __construct(TrainerRating $model)
    {
        $this->model = $model;
    }

    public function rateOrUpdate($data)
    {
        return $this->model::updateOrCreate(
            [
                'student_id'         => $data['student_id'],
                'trainer_id'         => $data['trainer_id'],
                'course_section_id'  => $data['course_section_id'],
            ],
            [
                'rating'  => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]
        );
    }

    public function getRatings($trainerId, $sectionId)
    {
        return $this->model::with('student:id,name,photo')
            ->where('trainer_id', $trainerId)
            ->where('course_section_id', $sectionId)
            ->get();
    }

    public function getAverageRating($trainerId, $sectionId)
    {
        return $this->model::where('trainer_id', $trainerId)
            ->where('course_section_id', $sectionId)
            ->avg('rating');
    }
     public function getTrainerRatingsStatistics($startDate = null, $endDate = null, $limit = null)
    {
        $query = $this->model
            ->select(
                'trainer_id',
                DB::raw('AVG(rating) as average_rating'),
                DB::raw('COUNT(rating) as total_ratings')
            )
            ->with('trainer:id,name')
            ->groupBy('trainer_id');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        
        $query->orderByDesc('average_rating');

        
        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }
}
