<?php

namespace App\Services;

use App\Models\Trainer;
use App\Repositories\SessionRepository;
use App\Repositories\CourseSectionRepository;
use Illuminate\Support\Facades\Auth;

class SessionService
{
    protected $sessionRepository;
    protected $courseSectionRepository;

    public function __construct(
        SessionRepository $sessionRepository,
        CourseSectionRepository $courseSectionRepository
    ) {
        $this->sessionRepository = $sessionRepository;
        $this->courseSectionRepository = $courseSectionRepository;
    }

    public function createSession($request)
    {
        $user = Auth::user();
        
        if (!($user instanceof Trainer)) {
            throw new \Exception('Only trainers can create sessions');
        }

        $section = $this->courseSectionRepository->getById($request->course_section_id);

        // Verify trainer is assigned to this section
        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to create sessions for this section');
        }

        $session = $this->sessionRepository->create([
            'name' => $request->name,
            'session_date' => $request->session_date,
            'course_section_id' => $request->course_section_id
        ]);

        return response()->json([
            'message' => 'Session created successfully',
            'session' => $session
        ]);
    }

    public function getSessionsBySection($sectionId, $request)
    {
        $user = Auth::user();
        $section = $this->courseSectionRepository->getById($sectionId);

        // If user is a trainer, verify they are assigned to this section
        if ($user instanceof Trainer) {
            if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
                throw new \Exception('You are not authorized to view sessions for this section');
            }
        }

        $perPage = $request->input('per_page', 10);
        $sessions = $this->sessionRepository->getBySection($sectionId, $perPage);
        
        return response()->json([
            'message' => 'Sessions retrieved successfully',
            'sessions' => $sessions
        ]);
    }

    public function updateSession($request, $sessionId)
    {
        $user = Auth::user();
        
        if (!($user instanceof Trainer)) {
            throw new \Exception('Only trainers can update sessions');
        }

        $session = $this->sessionRepository->getById($sessionId);
        if (!$session) {
            throw new \Exception('Session not found');
        }

        $section = $this->courseSectionRepository->getById($session->course_section_id);

        // Verify trainer is assigned to this section
        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to update sessions for this section');
        }

        $updated = $this->sessionRepository->update($sessionId, [
            'name' => $request->name,
            'session_date' => $request->session_date
        ]);

        return response()->json([
            'message' => 'Session updated successfully',
            'session' => $this->sessionRepository->getById($sessionId)
        ]);
    }

    public function deleteSession($sessionId)
    {
        $user = Auth::user();
        
        if (!($user instanceof Trainer)) {
            throw new \Exception('Only trainers can delete sessions');
        }

        $session = $this->sessionRepository->getById($sessionId);
        if (!$session) {
            throw new \Exception('Session not found');
        }

        $section = $this->courseSectionRepository->getById($session->course_section_id);

        // Verify trainer is assigned to this section
        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to delete sessions for this section');
        }

        $this->sessionRepository->delete($sessionId);

        return response()->json([
            'message' => 'Session deleted successfully'
        ]);
    }
} 