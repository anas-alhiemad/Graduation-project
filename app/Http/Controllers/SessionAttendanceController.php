<?php

namespace App\Http\Controllers;

use App\Services\Attendance\MarkAttendanceService;
use App\Services\Attendance\DisplayAttendanceService;
use App\Services\Attendance\EditAttendanceService;
use App\Services\Attendance\DeleteAttendanceService;
use App\Services\Attendance\AttendanceStatisticsService;
use App\Http\Requests\Attendance\MarkAttendanceRequest;
use App\Http\Requests\Attendance\EditAttendanceRequest;
use Illuminate\Http\Request;

class SessionAttendanceController extends Controller
{
    protected $markAttendanceService;
    protected $displayAttendanceService;
    protected $editAttendanceService;
    protected $deleteAttendanceService;
    protected $attendanceStatisticsService;

    public function __construct(
        MarkAttendanceService $markAttendanceService,
        DisplayAttendanceService $displayAttendanceService,
        EditAttendanceService $editAttendanceService,
        DeleteAttendanceService $deleteAttendanceService,
        AttendanceStatisticsService $attendanceStatisticsService
    ) {
        $this->markAttendanceService = $markAttendanceService;
        $this->displayAttendanceService = $displayAttendanceService;
        $this->editAttendanceService = $editAttendanceService;
        $this->deleteAttendanceService = $deleteAttendanceService;
        $this->attendanceStatisticsService = $attendanceStatisticsService;
    }

    public function markAttendance(MarkAttendanceRequest $request, $sessionId)
    {
        return $this->markAttendanceService->markAttendance($request, $sessionId);
    }

    public function getSessionAttendance($sessionId)
    {
        return $this->displayAttendanceService->getSessionAttendance($sessionId);
    }

    public function getStudentAttendance($studentId, Request $request)
    {
        return $this->displayAttendanceService->getStudentAttendance($studentId, $request);
    }

    public function getSectionAttendance($sectionId, Request $request)
    {
        return $this->displayAttendanceService->getSectionAttendance($sectionId, $request);
    }

    public function editAttendance(EditAttendanceRequest $request, $attendanceId)
    {
        return $this->editAttendanceService->editAttendance($request, $attendanceId);
    }

    public function deleteAttendance($attendanceId)
    {
        return $this->deleteAttendanceService->deleteAttendance($attendanceId);
    }

    public function getStudentAttendanceStats($studentId, $sectionId)
    {
        return $this->attendanceStatisticsService->getStudentAttendanceStats($studentId, $sectionId);
    }

    public function getSectionAttendanceStats($sectionId)
    {
        return $this->attendanceStatisticsService->getSectionAttendanceStats($sectionId);
    }
}
