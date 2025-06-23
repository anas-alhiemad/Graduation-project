<?php

namespace App\Repositories;

use App\Models\SectionRating;

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
}
