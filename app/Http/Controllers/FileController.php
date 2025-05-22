<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FileServices\UploadFileService;

class FileController extends Controller
{
    protected $uploadFileService;

    public function __construct(UploadFileService $uploadFileService)
    {
        $this->uploadFileService = $uploadFileService;
    }
}
