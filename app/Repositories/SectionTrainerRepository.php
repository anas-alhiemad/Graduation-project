<?php
namespace App\Repositories;

use App\Models\SectionTrainer;
use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;

class SectionTrainerRepository  extends BaseRepository implements RepositoryInterface
{
    public function __construct(SectionTrainer $model)
    {
        parent::__construct($model);
    }

    public function removeTrainerFromSection($section_id, $trainer_id)
    {
     $this->model->where('course_section_id',$section_id)->where('trainer_id',$trainer_id)->delete();
     return true;
    }

    public function exists(array $conditions)
{
    return $this->model::where($conditions)->exists();
}
}