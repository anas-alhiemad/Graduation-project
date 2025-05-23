<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;
use App\Models\Section_File;

class FileRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(Section_File $model)
    {
        parent::__construct($model);
    }

    public function getAllBySectionId($section_Id)
    {
        return $this->model->where('course_section_id',$section_Id)->paginate(10);
    }
}