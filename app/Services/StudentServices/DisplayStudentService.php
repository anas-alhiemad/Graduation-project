<?php
namespace App\Services\StudentServices;

use App\Repositories\StudentRepository;

class DisplayStudentService 
{
    protected $studentRepository;
    
    public function __construct(StudentRepository  $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function indexStudent() 
    {
        $student = $this->studentRepository->getAll();
        return response()->json([
            "message" => "All Students in the System.",
            "Students" => $student]);
    }

    public function getStudentById($studentId)
    {
        $student = $this->studentRepository->getById($studentId);
        return response()->json([
            "message" => "the Student .",
            "student" => $student]);
    }


    public function searchStudent($request)
    {

        $students = $this->studentRepository->search($request);

        return response()->json(["message"=>"Search results","Students" => $students]);
    }

}