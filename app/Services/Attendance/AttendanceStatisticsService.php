<?php

namespace App\Services\Attendance;

use App\Models\Student;
use App\Models\Trainer;
use App\Repositories\SessionAttendanceRepository;
use App\Repositories\CourseSectionRepository;
use Illuminate\Support\Facades\Auth;

class AttendanceStatisticsService
{
    protected $sessionAttendanceRepository;
    protected $courseSectionRepository;

    public function __construct(
        SessionAttendanceRepository $sessionAttendanceRepository,
        CourseSectionRepository $courseSectionRepository
    ) {
        $this->sessionAttendanceRepository = $sessionAttendanceRepository;
        $this->courseSectionRepository = $courseSectionRepository;
    }

    public function getStudentAttendanceStats($studentId, $sectionId)
    {
        $user = Auth::user();

        
        if ($user instanceof Student && $user->id != $studentId) {
            throw new \Exception('You are not authorized to view these statistics');
        }

        if ($user instanceof Trainer) {
            $section = $this->courseSectionRepository->getById($sectionId);
            if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
                throw new \Exception('You are not authorized to view these statistics');
            }
        }

        $stats = $this->sessionAttendanceRepository->getStudentAttendanceStats($studentId, $sectionId);

        return response()->json([
            'message' => 'Student attendance statistics retrieved successfully',
            'statistics' => $stats,
        ]);
    }

    public function getSectionAttendanceStats($sectionId)
    {
        $user = Auth::user();

        if (!($user instanceof Trainer)) {
            throw new \Exception('Only trainers can view section statistics');
        }

        $section = $this->courseSectionRepository->getById($sectionId);
        if (!$section) {
            throw new \Exception('Section not found');
        }

        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to view these statistics');
        }

        $stats = $this->sessionAttendanceRepository->getSectionAttendanceStats($sectionId);

        return response()->json([
            'message' => 'Section attendance statistics retrieved successfully',
            'statistics' => $stats,
        ]);
    }
}
