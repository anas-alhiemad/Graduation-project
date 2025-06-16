<?php

namespace App\Http\Controllers;

use App\Services\SessionService;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    protected $sessionService;

    public function __construct(SessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    public function create(Request $request)
    {
        try {
            return $this->sessionService->createSession($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        }
    }

    public function getBySection(Request $request, $sectionId)
    {
        try {
            return $this->sessionService->getSessionsBySection($sectionId, $request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        }
    }

    public function update(Request $request, $sessionId)
    {
        try {
            return $this->sessionService->updateSession($request, $sessionId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        }
    }

    public function delete($sessionId)
    {
        try {
            return $this->sessionService->deleteSession($sessionId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        }
    }
} 