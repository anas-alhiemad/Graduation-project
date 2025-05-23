<?php
namespace App\Services\FileServices;

use Illuminate\Support\Facades\Storage;
use App\Repositories\FileRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Repositories\CourseSectionRepository;

class UpdateFileService 
{
    protected $fileRepository;
    protected $courseSectionRepository;

    public function __construct(FileRepository $fileRepository,CourseSectionRepository $courseSectionRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->courseSectionRepository = $courseSectionRepository;

    }

    public function updateFile($request) 
    {
        $fileRecord = $this->fileRepository->getById($request->file_Id);
        $section = $this->courseSectionRepository->getById($request->course_section_id);
        $trainer = Auth::guard('trainer')->user();

        if (Gate::forUser($trainer)->denies('upload', $section)) {
            abort(403, 'Unauthorized to update this file to this section.');
        }

        if ($fileRecord->file_path && file_exists(public_path($fileRecord->file_path))) {
            unlink(public_path($fileRecord->file_path));
        }

        
        $path = 'upload/' . $request->file('file')->store('section_file', 'public_upload');
        $file_name = $request->file('file')->getClientOriginalName();    
        $data_file = ["file_name" => $file_name ,"file_path" => $path,"course_section_id" =>$request->course_section_id];

        $file = $this->fileRepository->update($request->file_Id,$data_file);
        
        return response()->json([
            "message" => "File has been updated successfuly ",
            "file" => $file],200);
    }



}