<?php
namespace App\Repositories;

use App\Models\QuizQuestion;
use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;

class QuizQuestionRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(QuizQuestion $model)
    {
        parent::__construct($model);
    }
}