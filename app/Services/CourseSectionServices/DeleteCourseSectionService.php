<?php
namespace App\Services\CourseSectionServices;

use App\Repositories\CourseSectionRepository;

class DeleteCourseSectionService 
{
    protected $courseSectionRepository;
    
    public function __construct(CourseSectionRepository  $courseSectionRepository)
    {
        $this->courseSectionRepository = $courseSectionRepository;
    }

    public function deletingSection($section_id) 
    {
        $this->courseSectionRepository->delete($section_id);

        return response()->json([
            'message' => 'The section has been deleted successfully',
        ], 200);
    }


}