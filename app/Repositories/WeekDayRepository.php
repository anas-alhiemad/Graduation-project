<?php
namespace App\Repositories;

use App\Models\WeekDay;
use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;

class WeekDayRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(WeekDay $model)
    {
        parent::__construct($model);
    }

    public function dayNameToId() 
    {
        return $this->model::pluck('id', 'name')->toArray();
    }

}