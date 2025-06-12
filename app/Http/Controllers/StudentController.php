<?php

namespace App\Http\Controllers;

use App\Services\StudentServices\DisplayStudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $displayStudentService;

    public function __construct(DisplayStudentService $displayStudentService)
    {
        $this->displayStudentService = $displayStudentService;
    }

    public function getMyProfile()
    {
        return $this->displayStudentService->getMyProfile();
    }
} 