<?php
namespace App\Services\FileServices;

use App\Repositories\FileRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Repositories\CourseSectionRepository;

class DeleteFileService 
{
    protected $fileRepository;
    protected $courseSectionRepository;

    public function __construct(FileRepository $fileRepository,CourseSectionRepository $courseSectionRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->courseSectionRepository = $courseSectionRepository;

    }


    public function deleteFile($file_id) 
    {
        $fileRecord = $this->fileRepository->getById($file_id);
        $section = $this->courseSectionRepository->getById($fileRecord->course_section_id);
        $trainer = Auth::guard('trainer')->user();

        if (Gate::forUser($trainer)->denies('upload', $section)) {
            abort(403, 'Unauthorized to update this file to this section.');
        }

        if ($fileRecord->file_path && file_exists(public_path($fileRecord->file_path))) {
            unlink(public_path($fileRecord->file_path));
        }


        
        $file = $this->fileRepository->delete($file_id);


        return response()->json([
            "message" => "File has been deleted successfuly "],200);
    }

    

}