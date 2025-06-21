<?php
namespace App\Services\CourseSectionServices;

use App\Repositories\TrainerRepository;
use App\Repositories\WeekDayRepository;
use App\Repositories\CourseSectionRepository;
use App\Repositories\SectionTrainerRepository;

class SectionTrainerService 
{
    protected $sectionTrainerRepository;
    protected $courseSectionRepository;
    protected $trainerRepository;
    protected $weekDayRepository;

    public function __construct(CourseSectionRepository $courseSectionRepository,SectionTrainerRepository $sectionTrainerRepository,TrainerRepository  $trainerRepository, WeekDayRepository $weekDayRepository)
    {
        $this->courseSectionRepository = $courseSectionRepository;
        $this->sectionTrainerRepository = $sectionTrainerRepository;
        $this->trainerRepository = $trainerRepository;
        $this->weekDayRepository = $weekDayRepository;
      
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

    public function getTrainerCourses($trainerId)
    {
        $courses = $this->courseSectionRepository->getTrainerCourses($trainerId);
        return response()->json([
            'message' => "Courses that trainer teaches",
            'courses' => $courses
        ]);
    }


public function indexTrainerWithCourse() 
{
    $trainersWithCourse = $this->courseSectionRepository->getAllTrainerCourses();

    $trainersWithCourse->setCollection(
        $trainersWithCourse->getCollection()
            ->map(function ($section) {
                return [
                    'trainer_id' => $section->trainers[0]->id ?? null,
                    'trainer' => $section->trainers[0] ?? null,
                    'course_id' => $section->course->id,
                    'course' => $section->course,
                ];
            })
            ->unique(fn($item) => $item['course_id'] . '-' . $item['trainer_id'])
            ->values()
    );

    return response()->json([
        "message" => "All Trainers in the System with Course.",
        "Trainers" => $trainersWithCourse
    ]);
}

    public function getTrainerSchedule($name_day)
    {
        $dayNameToId = $this->weekDayRepository->dayNameToId();

        if (!isset($dayNameToId[$name_day])) {
            return response()->json(['message' => 'Invalid day name'], 400);
        }

        $dayId = $dayNameToId[$name_day];
        $trainer = auth()->user();

        $schedule = $this->trainerRepository->getSchedule($trainer);

        $events = [];

        foreach ($schedule as $section) {
            foreach ($section->weekDays as $day) {
                if ($day->id != $dayId) {
                    continue;
                }

                $events[] = [
                    'course'     => $section->course,
                    'section' => collect($section)->only(['id', 'name', 'seatsOfNumber', 'reservedSeats', 'state',
                                                        'startDate', 'endDate', 'courseId', 'created_at', 'updated_at']),
                    'day'        => $day,
                    'start_time' => $day->pivot->start_time,
                    'end_time'   => $day->pivot->end_time,
                ];
            }
        }

        return response()->json([
            'message' => "Schedule your tasks today",'Events' => $events ],200);       
    }

}