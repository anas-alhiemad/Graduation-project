<?php
namespace App\Services\CourseSectionServices;

use App\Repositories\WeekDayRepository;
use App\Repositories\CourseSectionRepository;

class CreateCourseSectionService 
{
    protected $courseSectionRepository;
    protected $weekDayRepository;
    
    public function __construct(CourseSectionRepository  $courseSectionRepository,WeekDayRepository $weekDayRepository)
    {
        $this->courseSectionRepository = $courseSectionRepository;
        $this->weekDayRepository = $weekDayRepository;
    }


    public function store($request) 
    {
        $dataSection = $request->except('days');
        $dataSectionCreated =$this->courseSectionRepository->create($dataSection);
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
        $dataSectionCreated->refresh();
        $dataSectionCreated->weekDays()->sync($syncData);
        $dataSectionCreated->load('weekDays');
        return response()->json([
            'message' => 'The section has been created successfully',
            'data'    => $dataSectionCreated->only([
                'id', 'name', 'seatsOfNumber', 'startDate', 'endDate','state','courseId', 'created_at', 'updated_at'
            ]) + [
                'week_days' => $dataSectionCreated->formatted_week_days,
            ],
        ], 200);
    }
}