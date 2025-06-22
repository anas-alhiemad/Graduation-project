<?php

namespace App\Services\Attendance;

use App\Models\Trainer;
use App\Repositories\SessionAttendanceRepository;
use App\Repositories\SessionRepository;
use App\Repositories\CourseSectionRepository;
use Illuminate\Support\Facades\Auth;

class DeleteAttendanceService
{
    protected $sessionAttendanceRepository;
    protected $sessionRepository;
    protected $courseSectionRepository;

    public function __construct(
        SessionAttendanceRepository $sessionAttendanceRepository,
        SessionRepository $sessionRepository,
        CourseSectionRepository $courseSectionRepository
    ) {
        $this->sessionAttendanceRepository = $sessionAttendanceRepository;
        $this->sessionRepository = $sessionRepository;
        $this->courseSectionRepository = $courseSectionRepository;
    }

    public function deleteAttendance($attendanceId)
    {
        $user = Auth::user();

        if (!($user instanceof Trainer)) {
            throw new \Exception('Only trainers can delete attendance records');
        }

        $attendance = $this->sessionAttendanceRepository->getById($attendanceId);
        if (!$attendance) {
            throw new \Exception('Attendance record not found');
        }

        $session = $this->sessionRepository->getById($attendance->session_id);
        $section = $this->courseSectionRepository->getById($session->course_section_id);

        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to delete attendance for this session');
        }

        $this->sessionAttendanceRepository->delete($attendanceId);

        return response()->json([
            'message' => 'Attendance record deleted successfully',
        ]);
    }
}
