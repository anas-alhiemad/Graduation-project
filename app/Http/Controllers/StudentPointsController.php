<?php

namespace App\Http\Controllers;

use App\Services\StudentServices\DisplayStudentService;
use Illuminate\Http\Request;

class StudentPointsController extends Controller
{
    protected $displayStudentService;

    public function __construct(DisplayStudentService $displayStudentService)
    {
        $this->displayStudentService = $displayStudentService;
    }

    public function getPoints(Request $request)
    {
        return $this->displayStudentService->getStudentPoints($request->user()->id);
    }
} 