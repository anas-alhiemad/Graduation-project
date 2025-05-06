<?php
namespace App\Services\CourseSectionServices;

use App\Repositories\CourseSectionRepository;
use App\Repositories\SectionTrainerRepository;

class SectionTrainerService 
{
    protected $sectionTrainerRepository;
    protected $courseSectionRepository;
    
    public function __construct(CourseSectionRepository $courseSectionRepository,SectionTrainerRepository $sectionTrainerRepository)
    {
        $this->courseSectionRepository = $courseSectionRepository;
        $this->sectionTrainerRepository = $sectionTrainerRepository;
      
    }


    

    public function registerTrainerToSection($request)
    {
        $sectionTrainer =$request->all() ;
        $exists = $this->sectionTrainerRepository->exists([
            'course_section_id' => $sectionTrainer['course_section_id'],
            'trainer_id' => $sectionTrainer['trainer_id']
        ]);
    
        if ($exists) {
            return response()->json(['message' => 'Trainer already registered in this section'], 409);
        }
        
        $this->sectionTrainerRepository->create($sectionTrainer);
        return response()->json(['message' => 'Trainer registered successfully']);
    }



    public function getTrainersInSection($section_id)
    {
        $trainersInSection = $this->courseSectionRepository->trainerInSection($section_id);
        return response()->json([
            'message' => "Trainer in section",
            'trainers' => $trainersInSection
        ]);
    }



    public function deleteTrainerFromSection($request)
    {
       $this->sectionTrainerRepository->removeTrainerFromSection($request->course_section_id,$request->trainer_id) ;

        return response()->json(['message' => 'Trainer removed from section']);
    }


}