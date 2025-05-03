<?php
namespace App\Services\StudentServices;

use App\Repositories\StudentRepository;

class DeleteStudentService 
{
    protected $studentRepository;
    
    public function __construct(StudentRepository  $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function deletingStudent ($studentId) 
    {
        $student = $this->studentRepository->getById($studentId);
        if ($student->photo && file_exists(public_path($student->photo))) {
            unlink(public_path($student->photo));
        }
        $this->studentRepository->delete($studentId);
        return response()->json(["message" => "Student has been deleted successfuly "],200);      

    }
}