<?php
namespace App\Services\FileServices;

use App\Repositories\FileRepository;
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
        $files = $this->fileRepository->getAllBySectionId($course_section_id);
        return response()->json([
            "message" => "All files in the section.",
            "Files" => $files]);
    }


    public function getFileById($file_Id)
    {
        $file = $this->fileRepository->getById($file_Id);
        return response()->json([
            "message" => "the file .",
            "file" => $file]);
    }
}