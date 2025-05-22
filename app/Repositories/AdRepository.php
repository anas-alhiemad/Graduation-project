<?php

namespace App\Repositories;

use App\Models\Ad;
use App\Interfaces\RepositoryInterface;

class AdRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(Ad $model)
    {
        parent::__construct($model);
    }

    public function getAll()
    {
        return $this->model->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $ad = $this->getById($id);
        $ad->update($data);
        return $ad;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
} 