<?php

namespace App\Services\SessionServices;

use Illuminate\Support\Facades\Auth;
use App\Models\Trainer;
use App\Repositories\SessionRepository;
use App\Repositories\CourseSectionRepository;

class UpdateSessionService
{
    protected $sessionRepository;
    protected $courseSectionRepository;

    public function __construct(SessionRepository $sessionRepository, CourseSectionRepository $courseSectionRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->courseSectionRepository = $courseSectionRepository;
    }

    public function update($request, $sessionId)
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

        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to update sessions for this section');
        }

        $this->sessionRepository->update($sessionId, $request->validated());

        return response()->json([
            'message' => 'Session updated successfully',
            'session' => $this->sessionRepository->getById($sessionId)
        ]);
    }
}
