<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Trainer;
use App\Repositories\SessionAttendanceRepository;
use App\Repositories\SessionRepository;
use App\Repositories\CourseSectionRepository;
use Illuminate\Support\Facades\Auth;

class SessionAttendanceService
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

        // Verify trainer is assigned to this section
        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to mark attendance for this session');
        }

        // Verify student is enrolled in this section
        $student = Student::find($request->student_id);
        if (!$student || !$section->students()->where('students.id', $student->id)->exists()) {
            throw new \Exception('Student is not enrolled in this section');
        }

        // Check if attendance record already exists
        $existingAttendance = $this->sessionAttendanceRepository->getBySessionAndStudent($sessionId, $request->student_id);
        
        if ($existingAttendance) {
            // Update existing attendance
            $this->sessionAttendanceRepository->update($existingAttendance->id, [
                'is_present' => $request->is_present
            ]);
        } else {
            // Create new attendance record
            $this->sessionAttendanceRepository->create([
                'session_id' => $sessionId,
                'student_id' => $request->student_id,
                'is_present' => $request->is_present
            ]);
        }

        return response()->json([
            'message' => 'Attendance marked successfully'
        ]);
    }

    public function getSessionAttendance($sessionId)
    {
        $user = Auth::user();
        $session = $this->sessionRepository->getById($sessionId);
        
        if (!$session) {
            throw new \Exception('Session not found');
        }

        $section = $this->courseSectionRepository->getById($session->course_section_id);

        // If user is a trainer, verify they are assigned to this section
        if ($user instanceof Trainer) {
            if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
                throw new \Exception('You are not authorized to view attendance for this session');
            }
        }
        // If user is a student, verify they are enrolled in this section
        else if ($user instanceof Student) {
            if (!$section->students()->where('students.id', $user->id)->exists()) {
                throw new \Exception('You are not authorized to view attendance for this session');
            }
        }

        $attendance = $this->sessionAttendanceRepository->getBySession($sessionId);
        
        return response()->json([
            'message' => 'Attendance retrieved successfully',
            'attendance' => $attendance
        ]);
    }

    public function getStudentAttendance($studentId, $request)
    {
        $user = Auth::user();
        
        // If user is a trainer, they can view any student's attendance
        if ($user instanceof Trainer) {
            $attendance = $this->sessionAttendanceRepository->getByStudent($studentId, $request->input('per_page', 10));
            return response()->json([
                'message' => 'Student attendance retrieved successfully',
                'attendance' => $attendance
            ]);
        }
        // If user is a student, they can only view their own attendance
        else if ($user instanceof Student && $user->id == $studentId) {
            $attendance = $this->sessionAttendanceRepository->getByStudent($studentId, $request->input('per_page', 10));
            return response()->json([
                'message' => 'Your attendance retrieved successfully',
                'attendance' => $attendance
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

        // Verify trainer is assigned to this section
        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to view attendance for this section');
        }

        $attendance = $this->sessionAttendanceRepository->getBySection($sectionId, $request->input('per_page', 10));
        
        return response()->json([
            'message' => 'Section attendance retrieved successfully',
            'attendance' => $attendance
        ]);
    }

    public function editAttendance($request, $attendanceId)
    {
        $user = Auth::user();
        
        if (!($user instanceof Trainer)) {
            throw new \Exception('Only trainers can edit attendance');
        }

        $attendance = $this->sessionAttendanceRepository->getById($attendanceId);
        if (!$attendance) {
            throw new \Exception('Attendance record not found');
        }

        $session = $this->sessionRepository->getById($attendance->session_id);
        $section = $this->courseSectionRepository->getById($session->course_section_id);

        // Verify trainer is assigned to this section
        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to edit attendance for this session');
        }

        $this->sessionAttendanceRepository->update($attendanceId, [
            'is_present' => $request->is_present
        ]);

        return response()->json([
            'message' => 'Attendance updated successfully',
            'attendance' => $this->sessionAttendanceRepository->getById($attendanceId)
        ]);
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

        // Verify trainer is assigned to this section
        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to delete attendance for this session');
        }

        $this->sessionAttendanceRepository->delete($attendanceId);

        return response()->json([
            'message' => 'Attendance record deleted successfully'
        ]);
    }

    // Helper functions
    public function getStudentAttendanceStats($studentId, $sectionId)
    {
        $user = Auth::user();
        
        // Verify authorization
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
            'statistics' => $stats
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
            'statistics' => $stats
        ]);
    }
} 