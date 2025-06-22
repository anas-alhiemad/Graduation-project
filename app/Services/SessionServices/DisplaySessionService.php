<?php

namespace App\Services\SessionServices;

use Illuminate\Support\Facades\Auth;
use App\Models\Trainer;
use App\Repositories\SessionRepository;
use App\Repositories\CourseSectionRepository;

class DisplaySessionService
{
    protected $sessionRepository;
    protected $courseSectionRepository;

    public function __construct(SessionRepository $sessionRepository, CourseSectionRepository $courseSectionRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->courseSectionRepository = $courseSectionRepository;
    }

    public function getBySection($sectionId, $request)
    {
        $user = Auth::user();

        $section = $this->courseSectionRepository->getById($sectionId);

        if ($user instanceof Trainer && !$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to view sessions for this section');
        }

        $perPage = $request->input('per_page', 10);
        $sessions = $this->sessionRepository->getBySection($sectionId, $perPage);

        return response()->json([
            'message' => 'Sessions retrieved successfully',
            'sessions' => $sessions
        ]);
    }
}
