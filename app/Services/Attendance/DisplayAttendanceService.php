<?php

namespace App\Services\Attendance;

use App\Models\Student;
use App\Models\Trainer;
use App\Repositories\SessionAttendanceRepository;
use App\Repositories\SessionRepository;
use App\Repositories\CourseSectionRepository;
use Illuminate\Support\Facades\Auth;

class DisplayAttendanceService
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

    public function getSessionAttendance($sessionId)
    {
        $user = Auth::user();

        $session = $this->sessionRepository->getById($sessionId);
        if (!$session) {
            throw new \Exception('Session not found');
        }

        $section = $this->courseSectionRepository->getById($session->course_section_id);

        if ($user instanceof Trainer) {
            if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
                throw new \Exception('You are not authorized to view attendance for this session');
            }
        } elseif ($user instanceof Student) {
            if (!$section->students()->where('students.id', $user->id)->exists()) {
                throw new \Exception('You are not authorized to view attendance for this session');
            }
        }

        $attendance = $this->sessionAttendanceRepository->getBySession($sessionId);

        return response()->json([
            'message' => 'Attendance retrieved successfully',
            'attendance' => $attendance,
        ]);
    }

    public function getStudentAttendance($studentId, $request)
    {
        $user = Auth::user();

        if ($user instanceof Trainer) {
            $attendance = $this->sessionAttendanceRepository->getByStudent($studentId, $request->input('per_page', 10));
            return response()->json([
                'message' => 'Student attendance retrieved successfully',
                'attendance' => $attendance,
            ]);
        } elseif ($user instanceof Student && $user->id == $studentId) {
            $attendance = $this->sessionAttendanceRepository->getByStudent($studentId, $request->input('per_page', 10));
            return response()->json([
                'message' => 'Your attendance retrieved successfully',
                'attendance' => $attendance,
            ]);
        }

        throw new \Exception('You are not authorized to view this attendance record');
    }

    public function getSectionAttendance($sectionId, $request)
    {
        $user = Auth::user();

        if (!($user instanceof Trainer)) {
            throw new \Exception('Only trainers can view section attendance');
        }

        $section = $this->courseSectionRepository->getById($sectionId);
        if (!$section) {
            throw new \Exception('Section not found');
        }

        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to view attendance for this section');
        }

        $attendance = $this->sessionAttendanceRepository->getBySection($sectionId, $request->input('per_page', 10));

        return response()->json([
            'message' => 'Section attendance retrieved successfully',
            'attendance' => $attendance,
        ]);
    }
}
