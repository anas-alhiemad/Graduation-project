<?php

namespace App\Services\SessionServices;

use Illuminate\Support\Facades\Auth;
use App\Models\Trainer;
use App\Repositories\SessionRepository;
use App\Repositories\CourseSectionRepository;

class CreateSessionService
{
    protected $sessionRepository;
    protected $courseSectionRepository;

    public function __construct(SessionRepository $sessionRepository, CourseSectionRepository $courseSectionRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->courseSectionRepository = $courseSectionRepository;
    }

public function create($request)
{
    $user = Auth::user();

    if (!($user instanceof Trainer)) {
        throw new \Exception('Only trainers can create sessions');
    }

    $section = $this->courseSectionRepository->getById($request->course_section_id);

     if ($section->state !== 'in_progress') {
        throw new \Exception('Cannot add sessions unless the section is in progress');
    }

    if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
        throw new \Exception('You are not authorized to create sessions for this section');
    }

    
    $currentSessionsCount = $section->sessions()->count();
    if ($currentSessionsCount >= $section->total_sessions) {
        throw new \Exception('You have reached the maximum number of sessions allowed for this course section');
    }

    $session = $this->sessionRepository->create($request->validated());

    return response()->json([
        'message' => 'Session created successfully',
        'session' => $session
    ]);
}


}
