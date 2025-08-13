<?php
namespace App\Services\CourseSectionServices;

use App\Repositories\WeekDayRepository;
use App\Repositories\CourseSectionRepository;
use App\Services\NotificationServices\SendNotificationsService;

class UpdateCourseSectionService 
{
    protected $courseSectionRepository;
    protected $weekDayRepository;
    protected $sendNotificationsService;

    public function __construct(CourseSectionRepository  $courseSectionRepository,WeekDayRepository $weekDayRepository,SendNotificationsService $sendNotificationsService)
    {
        $this->courseSectionRepository = $courseSectionRepository;
        $this->weekDayRepository = $weekDayRepository;
        $this->sendNotificationsService = $sendNotificationsService;
    }


    public function updateSection($section_id,$request)
    {
        $section = $this->courseSectionRepository->getById($section_id);

        $dataSection = $request->except('days');
        $this->courseSectionRepository->update($section_id,$dataSection);

        
        if ($request->has('days')) {
            $dayNameToId = $this->weekDayRepository->dayNameToId();

            $syncData = [];
            foreach ($request->input('days', []) as $dayName => $info) {
                if (!isset($dayNameToId[$dayName])) {
                    continue;
                }
                $dayId = $dayNameToId[$dayName];
                $syncData[$dayId] = [
                    'start_time' => $info['start_time'],
                    'end_time'   => $info['end_time'],
                ];
            }
            $section->weekDays()->sync($syncData);
        }
        $section->refresh();   
        $section->load('weekDays');
    
        
        return response()->json([
            'message' => 'The section has been created successfully',
            'data'    => $section->only([
                'id', 'name', 'seatsOfNumber', 'startDate', 'reservedSeats','endDate','state' ,'courseId', 'total_sessions', 'created_at', 'updated_at'
            ]) + [
                'week_days' => $section->formatted_week_days,
            ],
        ], 200);
    }



    public function updateStatus($section_id, $newState)
    {
       
        $courseSection = $this->courseSectionRepository->update($section_id, [
            'state' => $newState
        ]);

        
        $title = "Section status updated";
        $body = "The status of section '{$courseSection->name}' has been changed to '{$newState}'.";

        
        $students = $courseSection->students ;

        foreach ($students as $student) {
            if ($student->fcm_token) {
               
                $this->sendNotificationsService->sendByFcm($student->fcm_token, [
                    'title' => $title,
                    'body'  => $body,
                ]);

              
                $this->sendNotificationsService->storeNotification($student, $title, $body);
            }
        }

        
        $trainers = $courseSection->trainers;

        foreach ($trainers as $trainer) {
            if ($trainer->fcm_token) {
                $this->sendNotificationsService->sendByFcm($trainer->fcm_token, [
                    'title' => $title,
                    'body'  => $body,
                ]);

                $this->sendNotificationsService->storeNotification($trainer, $title, $body);
            }
        }

        
        return response()->json([
            'message' => 'Course Section status updated successfully.',
            'Course Section' => $courseSection
        ], 200);
    }


}