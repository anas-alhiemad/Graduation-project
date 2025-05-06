<?php
namespace App\Services\CourseSectionServices;

use App\Repositories\WeekDayRepository;
use App\Repositories\CourseSectionRepository;

class UpdateCourseSectionService 
{
    protected $courseSectionRepository;
    protected $weekDayRepository;
    
    public function __construct(CourseSectionRepository  $courseSectionRepository,WeekDayRepository $weekDayRepository)
    {
        $this->courseSectionRepository = $courseSectionRepository;
        $this->weekDayRepository = $weekDayRepository;
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
            'id', 'name', 'seatsOfNumber', 'startDate', 'endDate','state' ,'courseId', 'created_at', 'updated_at'
        ]) + [
            'week_days' => $section->formatted_week_days,
        ],
    ], 200);
}

}