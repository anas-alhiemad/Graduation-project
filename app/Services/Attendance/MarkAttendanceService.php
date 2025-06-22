<?php

namespace App\Services\Attendance;

use App\Models\Student;
use App\Models\Trainer;
use App\Repositories\SessionAttendanceRepository;
use App\Repositories\SessionRepository;
use App\Repositories\CourseSectionRepository;
use Illuminate\Support\Facades\Auth;

class MarkAttendanceService
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

    public function markAttendance($request, $sessionId)
    {
        $user = Auth::user();

        if (!($user instanceof Trainer)) {
            throw new \Exception('Only trainers can mark attendance');
        }

        $session = $this->sessionRepository->getById($sessionId);
        if (!$session) {
            throw new \Exception('Session not found');
        }

        $section = $this->courseSectionRepository->getById($session->course_section_id);

        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to mark attendance for this session');
        }

        $student = Student::find($request->student_id);
        if (!$student || !$section->students()->where('students.id', $student->id)->exists()) {
            throw new \Exception('Student is not enrolled in this section');
        }

        $existingAttendance = $this->sessionAttendanceRepository->getBySessionAndStudent($sessionId, $request->student_id);

        if ($existingAttendance) {
            $this->sessionAttendanceRepository->update($existingAttendance->id, [
                'is_present' => $request->is_present,
            ]);
        } else {
            $this->sessionAttendanceRepository->create([
                'session_id' => $sessionId,
                'student_id' => $request->student_id,
                'is_present' => $request->is_present,
            ]);
        }

        return response()->json(['message' => 'Attendance marked successfully']);
    }
}
