<?php
namespace App\Services\FileServices;

use App\Repositories\FileRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Repositories\CourseSectionRepository;

class DisplayFileService 
{
    protected $fileRepository;
    protected $courseSectionRepository;

    public function __construct(FileRepository $fileRepository,CourseSectionRepository $courseSectionRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->courseSectionRepository = $courseSectionRepository;

    }

    public function indexFiles($course_section_id)
    {
        $section = $this->courseSectionRepository->getById($course_section_id);
        $user = Auth::guard('trainer')->user() ?? Auth::guard('student')->user();
        
        if (Gate::forUser($user)->denies('view', $section )) {
            abort(403, 'You are not allowed to view this file.');
        }
        
        $files = $this->fileRepository->getAllBySectionId($course_section_id);
        return response()->json([
            "message" => "All files in the section.",
            "Files" => $files]);
    }


    public function getFileById($file_Id)
    {
        $file = $this->fileRepository->getById($file_Id);

        $section = $this->courseSectionRepository->getById($file->course_section_id);
        
        $user = Auth::guard('trainer')->user() ?? Auth::guard('student')->user();
        
        if (Gate::forUser($user)->denies('view', $section )) {
            abort(403, 'You are not allowed to view this file.');
        }
        
        return response()->json([
            "message" => "the file .",
            "file" => $file]);
    }
}