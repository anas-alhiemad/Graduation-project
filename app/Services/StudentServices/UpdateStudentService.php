<?php
namespace App\Services\StudentServices;

use App\Repositories\StudentRepository;

class UpdateStudentService 
{
    protected $studentRepository;
    
    public function __construct(StudentRepository  $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function updatingStudent($studentId,$request) 
    {
        $student = $this->studentRepository->getById($studentId);
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            if ($student->photo && file_exists(public_path($student->photo))) {
                unlink(public_path($student->photo));
            }
            $data['photo'] = 'upload/' . $request->file('photo')->store('studentPhoto', 'public_upload');
        }
        $studentInfo = $this->studentRepository->update($studentId,$data);
        return response()->json(["message" => "Student has been Updated successfuly ","studentInfo" => $studentInfo],200);      

    }
}