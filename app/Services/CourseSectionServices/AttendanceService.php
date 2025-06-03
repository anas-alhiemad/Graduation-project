<?php

namespace App\Services\CourseSectionServices;

use App\Models\Student;
use App\Models\Trainer;
use App\Models\CourseSection;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AttendanceService
{
    public function markAttendance($sectionId, $studentId, $isPresent, $date = null, $sessionTitle = null)
{
    $trainer = Auth::user();

    $isTrainerInSection = CourseSection::find($sectionId)
        ->trainers()
        ->where('trainers.id', $trainer->id)
        ->exists();

    if (!$isTrainerInSection) {
        throw new \Exception('You are not assigned to this section.');
    }

    $isStudentInSection = CourseSection::find($sectionId)
        ->students()
        ->where('students.id', $studentId)
        ->exists();

    if (!$isStudentInSection) {
        throw new \Exception('This student is not enrolled in this section.');
    }

    $date = $date ?? now()->toDateString();

    return DB::transaction(function () use ($trainer, $sectionId, $studentId, $isPresent, $date, $sessionTitle) {
        return Attendance::updateOrCreate(
            [
                'student_id' => $studentId,
                'course_section_id' => $sectionId,
                'date' => $date,
            ],
            [
                'trainer_id' => $trainer->id,
                'is_present' => $isPresent,
                'session_title' => $sessionTitle,
            ]
        );
    });
}


    public function getSectionAttendance($sectionId, $date = null)
    {
        $trainer = Auth::user();
        
        // Validate that the trainer is in the section
        $isTrainerInSection = CourseSection::find($sectionId)
            ->trainers()
            ->where('trainers.id', $trainer->id)
            ->exists();

        if (!$isTrainerInSection) {
            throw new \Exception('You are not assigned to this section.');
        }

        $query = Attendance::with(['student:id,name,photo'])
            ->where('course_section_id', $sectionId);

        if ($date) {
            $query->whereDate('date', $date);
        }

        return $query->get();
    }

    public function getStudentAttendance($studentId, $sectionId)
    {
        $trainer = Auth::user();
        
        // Validate that the trainer is in the section
        $isTrainerInSection = CourseSection::find($sectionId)
            ->trainers()
            ->where('trainers.id', $trainer->id)
            ->exists();

        if (!$isTrainerInSection) {
            throw new \Exception('You are not assigned to this section.');
        }

        return Attendance::where('student_id', $studentId)
            ->where('course_section_id', $sectionId)
            ->orderBy('date', 'desc')
            ->get();
    }
} 