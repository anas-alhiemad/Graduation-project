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
        // $sectionStudent =$request->all() ;
        $section = $this->courseSectionRepository->getById($request->course_section_id);

        if ($section->reservedSeats >= $section->seatsOfNumber) {
            return response()->json(['message' => 'No available seats'], 400);
        }

        $exists = $this->sectionStudentRepository->exists([
            'course_section_id' => $request->course_section_id,
            'student_id' => $request->student_id
        ]);
    
        if ($exists) {
            return response()->json(['message' => 'Student already registered in this section'], 409);
        }

        $section->students()->attach($request->student_id, ['is_confirmed' => true]);        
        $this->courseSectionRepository->incrementSeat($request->course_section_id);
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
    
    public function getStudentsInSectionConfirmed($section_id)
    {
        $studentsInSection = $this->courseSectionRepository->studentsInSectionConfirmed($section_id);
        return response()->json([
            'message' => "Student in section are Confirmed",
            'students' => $studentsInSection
        ]);
    }

    public function deleteStudentFromSection($request)
    {
       $section = $this->courseSectionRepository->getById($request->course_section_id); 
       $section->students()->detach($request->student_id);
    //    $this->sectionStudentRepository->removeStudentFromSection($request->course_section_id,$request->student_id) ;
       $this->courseSectionRepository->decrementSeat($request->course_section_id);
        return response()->json(['message' => 'Student removed from section']);
    }


}