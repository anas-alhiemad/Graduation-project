<?php

namespace App\Services\CourseSectionServices;

use App\Repositories\CourseSectionRepository;

class CourseSectionProgressService
{
    protected $courseSectionRepository;

    public function __construct(CourseSectionRepository $courseSectionRepository)
    {
        $this->courseSectionRepository = $courseSectionRepository;
    }

    public function calculateProgress($sectionId): float
    {
        $section = $this->courseSectionRepository->findWithSessionsAndAttendances($sectionId);

        if (!$section) {
            throw new \Exception('Section not found');
        }

        $totalSessions = $section->total_sessions;

        if ($totalSessions == 0) {
            return 0;
        }

        $completedSessions = $section->sessions->filter(function ($session) {
            return $session->attendances->isNotEmpty();
        })->count();

        return round(($completedSessions / $totalSessions) * 100, 2);
    }

    public function getSectionProgress($sectionId): \Illuminate\Http\JsonResponse
{
    try {
        $progress = $this->calculateProgress($sectionId);

        return response()->json([
            'message' => 'Section progress retrieved successfully',
            'progress_percentage' => $progress,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
        ], 404);
    }
}

}
 