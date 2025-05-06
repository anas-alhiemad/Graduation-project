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

}