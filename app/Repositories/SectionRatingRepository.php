<?php

namespace App\Repositories;

use App\Models\SectionRating;
use Illuminate\Support\Facades\DB;

class SectionRatingRepository
{
    protected $model;

    public function __construct(SectionRating $model)
    {
        $this->model = $model;
    }

    public function rateOrUpdate($data)
    {
        return $this->model::updateOrCreate(
            [
                'student_id' => $data['student_id'],
                'course_section_id' => $data['course_section_id'],
            ],
            [
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]
        );
    }

    public function getRatings($sectionId)
    {
        return $this->model::with('student:id,name,photo')
            ->where('course_section_id', $sectionId)
            ->get();
    }

    public function getAverageRating($sectionId)
    {
        return $this->model::where('course_section_id', $sectionId)
            ->avg('rating');
    }

     public function getSectionsStatistics($startDate = null, $endDate = null, $limit = null)
{
  $query = $this->model
    ->select(
        'course_section_id',
        DB::raw('AVG(rating) as average_rating'),
        DB::raw('COUNT(rating) as total_ratings')
    )
    ->with(['courseSection' => function ($q) {
        $q->select('id', 'name', 'courseId')
          ->with(['course:id,name']);
    }])
    ->groupBy('course_section_id');

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
