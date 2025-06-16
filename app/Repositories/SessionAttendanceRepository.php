<?php

namespace App\Repositories;

use App\Models\SessionAttendance;

class SessionAttendanceRepository
{
    protected $model;

    public function __construct(SessionAttendance $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $attendance = $this->getById($id);
        if ($attendance) {
            $attendance->update($data);
            return true;
        }
        return false;
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getBySessionAndStudent($sessionId, $studentId)
    {
        return $this->model
            ->where('session_id', $sessionId)
            ->where('student_id', $studentId)
            ->first();
    }

    public function getBySession($sessionId)
    {
        return $this->model
            ->where('session_id', $sessionId)
            ->with('student')
            ->get();
    }

    public function getByStudent($studentId, $perPage = 10)
    {
        return $this->model
            ->where('student_id', $studentId)
            ->with(['session', 'session.courseSection'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getBySection($sectionId, $perPage = 10)
    {
        return $this->model
            ->whereHas('session', function ($query) use ($sectionId) {
                $query->where('course_section_id', $sectionId);
            })
            ->with(['student', 'session'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function delete($id)
    {
        $attendance = $this->getById($id);
        if ($attendance) {
            return $attendance->delete();
        }
        return false;
    }

    // Helper functions
    public function getStudentAttendanceStats($studentId, $sectionId)
    {
        return $this->model
            ->whereHas('session', function ($query) use ($sectionId) {
                $query->where('course_section_id', $sectionId);
            })
            ->where('student_id', $studentId)
            ->selectRaw('
                COUNT(*) as total_sessions,
                SUM(CASE WHEN is_present = 1 THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN is_present = 0 THEN 1 ELSE 0 END) as absent_count
            ')
            ->first();
    }

    public function getSectionAttendanceStats($sectionId)
    {
        return $this->model
            ->whereHas('session', function ($query) use ($sectionId) {
                $query->where('course_section_id', $sectionId);
            })
            ->selectRaw('
                COUNT(DISTINCT session_id) as total_sessions,
                COUNT(DISTINCT student_id) as total_students,
                SUM(CASE WHEN is_present = 1 THEN 1 ELSE 0 END) as total_present,
                SUM(CASE WHEN is_present = 0 THEN 1 ELSE 0 END) as total_absent
            ')
            ->first();
    }
} 