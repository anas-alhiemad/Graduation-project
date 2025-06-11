<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;
use App\Models\Quiz;

class QuizRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(Quiz $model)
    {
        parent::__construct($model);
    }

    public function getAllBySectionId($section_Id)
    {
        return $this->model->with('quizQuestion.quizQuestionOption')->where('course_section_id',$section_Id)->paginate(10);
    }
}