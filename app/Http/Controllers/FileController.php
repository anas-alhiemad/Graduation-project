<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FileServices\DeleteFileService;
use App\Services\FileServices\UpdateFileService;
use App\Services\FileServices\UploadFileService;
use App\Services\FileServices\DisplayFileService;
use App\Http\Requests\FileRequest\UpdateFileRequest;
use App\Http\Requests\FileRequest\UploadFileRequest;

class FileController extends Controller
{
    protected $uploadFileService;
    protected $updateFileService;
    protected $deleteFileService;
    protected $displayFileService;

    public function __construct(UploadFileService $uploadFileService,UpdateFileService $updateFileService,DeleteFileService $deleteFileService,DisplayFileService $displayFileService)
    {
        $this->uploadFileService = $uploadFileService;
        $this->updateFileService = $updateFileService;
        $this->deleteFileService = $deleteFileService;
        $this->displayFileService = $displayFileService;
    }

    public function ShowAllFileInSection($course_section_id) 
    {
        return $this->displayFileService->indexFiles($course_section_id);
    }

    public function ShowFileById($file_Id) 
    {
        return $this->displayFileService->getFileById($file_Id);
    }

    public function UploadFile(UploadFileRequest $request) 
    {
        return $this->uploadFileService->store($request);
    }

    public function UpdateFile(UpdateFileRequest $request) 
    {
        return $this->updateFileService->updateFile($request);
    }

    public function DeleteFile($file_Id) 
    {
        return $this->deleteFileService->deleteFile($file_Id);
    }

}
