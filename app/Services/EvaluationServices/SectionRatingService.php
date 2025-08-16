<?php
namespace App\Services\EvaluationServices;

use App\Models\SectionRating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\SectionRatingRepository;

class SectionRatingService
{
    protected $sectionRatingRepository;
public function __construct(SectionRatingRepository $sectionRatingRepository)
{
    $this->sectionRatingRepository = $sectionRatingRepository;
}

    public function rateSection($sectionId, $rating, $comment = null)
    {
        $student = Auth::user();

        $isInSection = $student->sections()
            ->where('course_sections.id', $sectionId)
            ->exists();

        if (!$isInSection) {
            throw new \Exception('You are not enrolled in this section.');
        }

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
     public function getSectionsStatistics($startDate = null, $endDate = null, $limit = null)
    {
        $stats = $this->sectionRatingRepository->getSectionsStatistics($startDate, $endDate, $limit);

        return $stats->map(function ($row) {
            return [
                'section_id'     => $row->course_section_id,
                'section_name'   => $row->courseSection->name ?? 'N/A',
                'average_rating' => round($row->average_rating, 2),
                'total_ratings'  => $row->total_ratings,
            ];
        });
          }
}
