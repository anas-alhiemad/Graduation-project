<?php

namespace App\Http\Controllers;

use App\Services\secretaryServices\DisplaySecretaryService;
use Illuminate\Http\Request;

class SecretaryPointsController extends Controller
{
    protected $displaySecretaryService;

    public function __construct(DisplaySecretaryService $displaySecretaryService)
    {
        $this->displaySecretaryService = $displaySecretaryService;
    }

    public function getPoints(Request $request)
    {
        return $this->displaySecretaryService->getSecretaryPoints($request->user()->id);
    }
} 