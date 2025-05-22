<?php
namespace App\Services\FileServices;

use App\Repositories\FileRepository;

class UploadFileService 
{
    protected $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;

    }

    
}