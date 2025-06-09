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
}