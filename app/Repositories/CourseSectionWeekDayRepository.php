<?php
namespace App\Repositories;

use App\Models\CourseSectionWeekDay;
use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;

class CourseSectionWeekDayRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(CourseSectionWeekDay $model)
    {
        parent::__construct($model);
    }
}