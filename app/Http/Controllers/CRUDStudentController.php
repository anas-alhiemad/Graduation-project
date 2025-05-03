<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StudentServices\DeleteStudentService;
use App\Services\StudentServices\UpdateStudentService;
use App\Services\StudentServices\DisplayStudentService;
use App\Http\Requests\StudentRequest\UpdateStudentRequest;

class CRUDStudentController extends Controller
{
    protected $updateStudentService;
    protected $deleteStudentService;
    protected $displayStudentService;

    public function __construct(DisplayStudentService $displayStudentService,UpdateStudentService $updateStudentService,DeleteStudentService $deleteStudentService)
    {
        $this->updateStudentService = $updateStudentService;
        $this->deleteStudentService = $deleteStudentService;
        $this->displayStudentService = $displayStudentService;
    }

    public function ShowAllStudent() 
    {
        return $this->displayStudentService->indexStudent();
    }
    public function ShowStudentById($studentId) 
    {
        return $this->displayStudentService->getStudentById($studentId);
    }

    public function UpdateStudent($studentId,UpdateStudentRequest $request) 
    {
        return $this->updateStudentService->updatingStudent($studentId,$request);
    }

    public function DeleteStudent($studentId) 
    {
        return $this->deleteStudentService->deletingStudent($studentId);
    }
    
    public function SearchStudent($querySearch) 
    {
        return $this->displayStudentService->SearchStudent($querySearch);
    }

}
