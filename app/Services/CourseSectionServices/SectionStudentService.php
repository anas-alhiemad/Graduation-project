<?php
namespace App\Services\CourseSectionServices;

use App\Repositories\CourseSectionRepository;
use App\Repositories\SectionStudentRepository;
use App\Repositories\SectionTrainerRepository;

class SectionStudentService 
{
    protected $sectionStudentRepository;
    protected $courseSectionRepository;
    protected $sectionTrainerRepository;
    
    public function __construct(SectionStudentRepository  $sectionStudentRepository,CourseSectionRepository $courseSectionRepository,SectionTrainerRepository $sectionTrainerRepository)
    {
        $this->sectionStudentRepository = $sectionStudentRepository;
        $this->courseSectionRepository = $courseSectionRepository;
        $this->sectionTrainerRepository = $sectionTrainerRepository;
      
    }

    public function registerStudentToSection($request)
    {
        $sectionStudent =$request->all() ;
        $sectionStudent ['is_confirmed'] = true;
        $sectionStudent;
        $exists = $this->sectionStudentRepository->exists([
            'course_section_id' => $sectionStudent['course_section_id'],
            'student_id' => $sectionStudent['student_id']
        ]);
    
        if ($exists) {
            return response()->json(['message' => 'Student already registered in this section'], 409);
        }
        
        $this->sectionStudentRepository->create($sectionStudent);
        return response()->json(['message' => 'Student registered successfully']);
    }

    public function getStudentsInSection($section_id)
    {
        $studentsInSection = $this->courseSectionRepository->studentsInSection($section_id);
        return response()->json([
            'message' => "Student in section",
            'students' => $studentsInSection
        ]);
    }

    public function deleteStudentFromSection($request)
    {
       $this->sectionStudentRepository->removeStudentFromSection($request->course_section_id,$request->student_id) ;

        return response()->json(['message' => 'Student removed from section']);
    }


}