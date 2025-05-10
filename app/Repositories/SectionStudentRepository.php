<?php
namespace App\Repositories;

use App\Models\SectionStudent;
use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;

class SectionStudentRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(SectionStudent $model)
    {
        parent::__construct($model);
    }

    public function removeStudentFromSection($section_id, $student_id)
    {
     $this->model->where('course_section_id',$section_id)->where('student_id',$student_id)->delete();
     return true;
    }

    public function exists(array $conditions)
    {
        return $this->model::where($conditions)->exists();
    }

    public function confirm($reservationId)
    {
        $is_confirmed = true;
        $this->model::where('id',$reservationId)->update( $is_confirmed);
        return true;
    }


        public function showReservation($section_student_id) 
        {
            return $this->model
                ->join('students', 'students.id', '=', 'section_students.student_id')
                ->where('section_students.id', $section_student_id)        
                ->select(
                'section_students.*',
                'students.name',
                'students.email',
                'students.phone',
                'students.photo',
                'students.birthday',
                'students.gender',
                'students.birthday'
                )->first();
        }


}