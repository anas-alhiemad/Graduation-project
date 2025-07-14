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
use App\Services\CourseSectionServices\CourseSectionProgressService;
use App\Services\CourseSectionServices\SecretaryArchiveService;

class CourseSectionController extends Controller
{
    protected $createCourseSectionService;
    protected $updatecourseSectionService;
    protected $displayCourseSectionService;
    protected $deleteCourseSectionService;
    protected $sectionStudentService;
    protected $sectionTrainerService;
    protected $progressService;
    public function __construct(CreateCourseSectionService $createCourseSectionService,UpdateCourseSectionService $updatecourseSectionService,DisplayCourseSectionService $displayCourseSectionService,DeleteCourseSectionService $deleteCourseSectionService,SectionStudentService $sectionStudentService,SectionTrainerService $sectionTrainerService,   CourseSectionProgressService $progressService, SecretaryArchiveService $secretaryArchiveService,)
    {
        $this->createCourseSectionService = $createCourseSectionService;
        $this->updatecourseSectionService = $updatecourseSectionService;
        $this->displayCourseSectionService = $displayCourseSectionService;
        $this->deleteCourseSectionService = $deleteCourseSectionService;
        $this->sectionStudentService = $sectionStudentService;
        $this->sectionTrainerService = $sectionTrainerService;
        $this->progressService = $progressService; 
        $this->secretaryArchiveService = $secretaryArchiveService;     
    }

    public function ShowAllCourseSection($courseId) 
    {
        return $this->displayCourseSectionService->indexSection($courseId);
    }
    public function ShowAllCourseSectionPending($courseId) 
    {
        return $this->displayCourseSectionService->indexSectionPending($courseId);
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
    
    public function GetMyScheduleByDayStudent($name_day) 
    {
        return $this->sectionStudentService->getStudentSchedule($name_day);
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

    public function GetMyScheduleByDayTrainer($name_day) 
    {
        return $this->sectionTrainerService->getTrainerSchedule($name_day);
    }

    public function getStudentCourses()
    {
        $studentId = auth()->user()->id;
        return $this->sectionStudentService->getStudentCourses($studentId);
    }

    public function getTrainerCourses()
    {
        $trainerId = auth()->user()->id;
        return $this->sectionTrainerService->getTrainerCourses($trainerId);
    }


    public function IndexTrainerWithCourse() 
    {
        return $this->sectionTrainerService->indexTrainerWithCourse();
    }

    public function GetCourseIsFinshed() 
    {
        return $this->sectionStudentService->getStudentCoursesFinshed();
    }
   public function showProgress($sectionId)
{
    try {
        $progress = $this->progressService->calculateProgress($sectionId);

        return response()->json([
            'message' => 'Section progress retrieved successfully',
            'progress_percentage' => $progress,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
        ], 404);
    }
}
public function getStudentArchiveBySecretary($studentId)
{
    return $this->secretaryArchiveService->getArchivedCoursesForStudent($studentId);
}

public function getTrainerArchiveBySecretary($trainerId)
{
    return $this->secretaryArchiveService->getArchivedCoursesForTrainer($trainerId);
}
    
}
