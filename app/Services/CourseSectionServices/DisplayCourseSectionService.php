<?php
namespace App\Services\CourseSectionServices;

use App\Repositories\CourseSectionRepository;

class DisplayCourseSectionService 
{
    protected $courseSectionRepository;
    
    public function __construct(CourseSectionRepository  $courseSectionRepository)
    {
        $this->courseSectionRepository = $courseSectionRepository;
    }



   public function indexSection($courseID)
{
    // جلب السكاشن مع العلاقات weekDays و trainers
    $sections = $this->courseSectionRepository->getAllByCourseId($courseID);
    $sections->load(['trainers', 'weekDays']); // تحميل العلاقات

    // تنسيق البيانات
    $formatted = $sections->map(function ($section) {
        return $section->only([
            'id', 'name', 'seatsOfNumber', 'startDate', 'reservedSeats','endDate','state','courseId', 'created_at', 'updated_at'
        ]) + [
            'week_days' => $section->formatted_week_days,
            'trainers' => $section->trainers->map(function ($trainer) {
                return $trainer->only(['id', 'name', 'email', 'specialization', 'experience', 'photo']);
            }),
        ];
    });

    return response()->json([
        "message" => "All sections in the course.",
        "sections" => $formatted,
    ]);
}

    public function indexSectionPending($courseID)
    {
      return  $sections = $this->courseSectionRepository->getAllByCourseIdIspending($courseID);

        $formatted = $sections->map(function ($section) {
            return $section->only([
                'id', 'name', 'seatsOfNumber', 'startDate', 'reservedSeats','endDate','state','courseId', 'created_at', 'updated_at'
            ]) + [
                'week_days' => $section->formatted_week_days,
            ];
        });

        return response()->json([
            "message" => "All sections in the course.",
            "sections" => $formatted,
        ]);
    }

  



    public function getSectionById($section_id)
    {
        $section = $this->courseSectionRepository->getById($section_id);
        $section->load('weekDays');
        $section->refresh();

        return response()->json([
            "message" => "The section in course.",
            "section" => $section->only([
                'id', 'name', 'seatsOfNumber', 'startDate','reservedSeats', 'endDate','state','courseId', 'created_at', 'updated_at'
            ]) + [
                'week_days' => $section->formatted_week_days,
            ],
        ]);
    }
}



