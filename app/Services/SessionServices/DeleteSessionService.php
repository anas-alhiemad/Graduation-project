<?php

namespace App\Services\SessionServices;

use Illuminate\Support\Facades\Auth;
use App\Models\Trainer;
use App\Repositories\SessionRepository;
use App\Repositories\CourseSectionRepository;

class DeleteSessionService
{
    protected $sessionRepository;
    protected $courseSectionRepository;

    public function __construct(SessionRepository $sessionRepository, CourseSectionRepository $courseSectionRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->courseSectionRepository = $courseSectionRepository;
    }

    public function delete($sessionId)
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

        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to delete sessions for this section');
        }

        $this->sessionRepository->delete($sessionId);

        return response()->json([
            'message' => 'Session deleted successfully'
        ]);
    }
}
