<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\CourseSectionServices\SectionStudentService;
use App\Services\CourseSectionServices\SectionTrainerService;
use App\Http\Requests\CourseSectionRequest\SectionStudentRequest;
use App\Http\Requests\CourseSectionRequest\SectionTrainertRequest;
use App\Services\CourseSectionServices\CreateCourseSectionService;
use App\Services\CourseSectionServices\DeleteCourseSectionService;
use App\Services\CourseSectionServices\UpdateCourseSectionService;
use App\Services\CourseSectionServices\DisplayCourseSectionService;
use App\Http\Requests\CourseSectionRequest\CreateCourseSectionRequest;
use App\Http\Requests\CourseSectionRequest\UpdateCourseSectionRequest;

class CourseSectionController extends Controller
{
    protected $createCourseSectionService;
    protected $updatecourseSectionService;
    protected $displayCourseSectionService;
    protected $deleteCourseSectionService;
    protected $sectionStudentService;
    protected $sectionTrainerService;

    public function __construct(CreateCourseSectionService $createCourseSectionService,UpdateCourseSectionService $updatecourseSectionService,DisplayCourseSectionService $displayCourseSectionService,DeleteCourseSectionService $deleteCourseSectionService,SectionStudentService $sectionStudentService,SectionTrainerService $sectionTrainerService)
    {
        $this->createCourseSectionService = $createCourseSectionService;
        $this->updatecourseSectionService = $updatecourseSectionService;
        $this->displayCourseSectionService = $displayCourseSectionService;
        $this->deleteCourseSectionService = $deleteCourseSectionService;
        $this->sectionStudentService = $sectionStudentService;
        $this->sectionTrainerService = $sectionTrainerService;
    }

    public function ShowAllCourseSection($courseId) 
    {
        return $this->displayCourseSectionService->indexSection($courseId);
    }
    public function ShowByIdCourseSection($sectionId) 
    {
        return $this->displayCourseSectionService->getSectionById($sectionId);
    }
    public function CreateCourseSection(CreateCourseSectionRequest $request) 
    {
        return $this->createCourseSectionService->store($request);
    }
    public function UpdateCourseSection($sectionId,UpdateCourseSectionRequest $request) 
    {
        return $this->updatecourseSectionService->updateSection($sectionId,$request);
    }
    public function DeleteCourseSection($sectionId) 
    {
        return $this->deleteCourseSectionService->deletingSection($sectionId);
    }


    public function RegisterStudentToSection(SectionStudentRequest $request) 
    {
        return $this->sectionStudentService->registerStudentToSection($request);
    }

    public function GetStudentsInSection($section_id) 
    {
        return $this->sectionStudentService->getStudentsInSection($section_id);
    }

    public function GetStudentsInSectionConfirmed($section_id) 
    {
        return $this->sectionStudentService->getStudentsInSectionConfirmed($section_id);
    }

    public function GetStudentInSection($section_id) 
    {
        return $this->sectionStudentService->getStudentsInSection($section_id);
    }

    public function DeleteStudentFromSection(SectionStudentRequest $request) 
    {
        return $this->sectionStudentService->deleteStudentFromSection($request);
    }

    

    public function RegisterTrainerToSection(SectionTrainertRequest $request) 
    {
        return $this->sectionTrainerService->registerTrainerToSection($request);
    }

    public function GetTrainersInSection($section_id) 
    {
        return $this->sectionTrainerService->getTrainersInSection($section_id);
    }

    public function DeleteTrainerFromSection(SectionTrainertRequest $request) 
    {
        return $this->sectionTrainerService->deleteTrainerFromSection($request);
    }

    


}
