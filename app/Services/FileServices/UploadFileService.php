<?php
namespace App\Services\FileServices;

use App\Repositories\FileRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Repositories\CourseSectionRepository;
use App\Services\NotificationServices\SendNotificationsService;

class UploadFileService 
{
    protected $fileRepository;
    protected $courseSectionRepository;
    protected $sendNotificationsService;

    public function __construct(FileRepository $fileRepository,CourseSectionRepository $courseSectionRepository,SendNotificationsService $sendNotificationsService)
    {
        $this->fileRepository = $fileRepository;
        $this->courseSectionRepository = $courseSectionRepository;
        $this->sendNotificationsService = $sendNotificationsService;
    }

    public function  store($request) 
    {
        
        $section = $this->courseSectionRepository->getById($request->course_section_id);
        $trainer = Auth::guard('trainer')->user();

        if (Gate::forUser($trainer)->denies('upload', $section)) {
            abort(403, 'Unauthorized to upload file to this section.');
        }

        // if (Gate::denies('upload', $section)) {
        //     abort(403, 'Unauthorized to upload file to this section.');}


        $path = 'upload/' . $request->file('file')->store('section_file', 'public_upload');
        $file_name = $request->file('file')->getClientOriginalName();    
        $data_file = ["file_name" => $file_name ,"file_path" => $path,"course_section_id" =>$request->course_section_id];
        
        $file = $this->fileRepository->create($data_file);

        $students = $section->students; 

        foreach ($students as $student) {
            if ($student->fcm_token) {
                $title = "A new file has been uploaded to the section." . $section->name;
                $body = "file name: " . $file_name;

                $this->sendNotificationsService->sendByFcm($student->fcm_token, [
                    'title' => $title,
                    'body' => $body,
                ]);

                $this->sendNotificationsService->storeNotification($student, $title, $body);
            }
    }

        return response()->json([
            "message" => "File has been uploaded successfuly ",
            "file" => $file],200);
    }

    
}