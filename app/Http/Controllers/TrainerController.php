<?php

namespace App\Http\Controllers;

use App\Services\TrainerServices\DisplayTrainerService;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    protected $displayTrainerService;

    public function __construct(DisplayTrainerService $displayTrainerService)
    {
        $this->displayTrainerService = $displayTrainerService;
    }

    public function getMyProfile()
    {
        return $this->displayTrainerService->getMyProfile();
    }
}
