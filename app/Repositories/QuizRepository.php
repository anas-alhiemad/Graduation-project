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
}