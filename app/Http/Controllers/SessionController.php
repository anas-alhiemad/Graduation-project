<?php

namespace App\Http\Controllers;

use App\Http\Requests\SessionRequest\CreateSessionRequest;
use App\Http\Requests\SessionRequest\UpdateSessionRequest;
use App\Services\SessionServices\CreateSessionService;
use App\Services\SessionServices\UpdateSessionService;
use App\Services\SessionServices\DeleteSessionService;
use App\Services\SessionServices\DisplaySessionService;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    protected $createSessionService;
    protected $updateSessionService;
    protected $deleteSessionService;
    protected $displaySessionService;

    public function __construct(
        CreateSessionService $createSessionService,
        UpdateSessionService $updateSessionService,
        DeleteSessionService $deleteSessionService,
        DisplaySessionService $displaySessionService
    ) {
        $this->createSessionService  = $createSessionService;
        $this->updateSessionService  = $updateSessionService;
        $this->deleteSessionService  = $deleteSessionService;
        $this->displaySessionService = $displaySessionService;
    }

    public function create(CreateSessionRequest $request)
    {
        try {
            return $this->createSessionService->create($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function getBySection(Request $request, $sectionId)
    {
        try {
            return $this->displaySessionService->getBySection($sectionId, $request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function update(UpdateSessionRequest $request, $sessionId)
    {
        try {
            return $this->updateSessionService->update($request, $sessionId);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function delete($sessionId)
    {
        try {
            return $this->deleteSessionService->delete($sessionId);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }
}
