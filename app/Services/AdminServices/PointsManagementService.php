<?php

namespace App\Services\AdminServices;

use App\Models\Student;
use App\Models\Secretary;
use Illuminate\Http\JsonResponse;

class PointsManagementService
{
    public function getTopStudents($limit = 10)
    {
        return Student::orderBy('points', 'desc')
                     ->take($limit)
                     ->get(['id', 'name', 'email', 'points']);
    }

    public function getTopSecretaries($limit = 10)
    {
        return Secretary::orderBy('points', 'desc')
                       ->take($limit)
                       ->get(['id', 'name', 'email', 'points']);
    }

    public function updateStudentPoints($studentId, $points)
    {
        $student = Student::findOrFail($studentId);
        $student->points += $points;
        $student->save();
        return $student;
    }

    public function updateSecretaryPoints($secretaryId, $points)
    {
        $secretary = Secretary::findOrFail($secretaryId);
        $secretary->points += $points;
        $secretary->save();
        return $secretary;
    }
} 