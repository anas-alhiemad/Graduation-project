<?php
namespace App\Repositories;

use App\Models\QuizQuestionOption;
use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;

class QuizQuestionOptionRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(QuizQuestionOption $model)
    {
        parent::__construct($model);
    }

    public function getAllQuestionOptions($quiz_question_id) 
    {
        return $this->model::where('quiz_question_id',$quiz_question_id)->get();
    }

    public function getByIdQuestionOptions($quiz_question_id) 
    {
        return $this->model::where('quiz_question_id',$quiz_question_id)->first();
    }
}