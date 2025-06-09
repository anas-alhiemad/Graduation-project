<?php
namespace App\Repositories;

use App\Models\CourseSection;
use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;

class CourseSectionRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(CourseSection $model)
    {
        parent::__construct($model);
    }

    public function getAllByCourseId($courseId)
    {
        return $this->model->with('weekDays')->where('courseId',$courseId)->paginate(10);
    }

    public function getAllByCourseIdIspending($courseId)
    {
        return $this->model->with('weekDays','trainers')->where('courseId',$courseId)
                                             ->where('state',"pending")->paginate(10);
    }

    public function incrementSeat($course_section_id)
    {
        return $this->model->where('id',$course_section_id)->increment('reservedSeats');
    }

    public function decrementSeat($course_section_id)
    {
        return $this->model->where('id',$course_section_id)->decrement('reservedSeats');
    }

    public function studentsInSection($section_id) 
    {
       return $this->model::where('id',$section_id)->with('students')->paginate(10);
    }

    public function studentsInSectionConfirmed($section_id) 
    {
        return $this->model::where('id', $section_id)
        ->with(['students' => function($query) {
            $query->wherePivot('is_confirmed', true); 
        }])->paginate(10);
    }

    public function showAllReservation($section_id) 
    {
        return $this->model::where('id', $section_id)
        ->with(['students' => function($query) {
            $query->wherePivot('is_confirmed', false); 
        }])->paginate(10);
    }

    public function trainerInSection($section_id) 
    {
       return $this->model::where('id',$section_id)->with('trainers')->get();
    }

    public function getStudentCourses($studentId)
    {
        return $this->model::whereHas('students', function($query) use ($studentId) {
            $query->where('student_id', $studentId)
                  ->where('is_confirmed', true);
        })->with(['course', 'weekDays'])->get();
    }

    public function getTrainerCourses($trainerId)
    {
        return $this->model::whereHas('trainers', function($query) use ($trainerId) {
            $query->where('trainer_id', $trainerId);
        })->with(['course', 'weekDays'])->get();
    }



    public function getAllTrainerCourses()
    {
        return  $this->model::whereHas('trainers')
                    ->with(['course', 'trainers'])
                    ->paginate(10);
    }


    public function getStudentCoursesIsFinished($studentId)
    {
        return $this->model::where('state','finished')->whereHas('students', function($query) use ($studentId) {
            $query->where('student_id', $studentId)
                ->where('is_confirmed', true);
        })->with(['course', 'weekDays'])->get();
    }
}