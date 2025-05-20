<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;
use App\Models\Gift;

class GiftRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(Gift $model)
    {
        parent::__construct($model);
    }

    public function getStudentGifts($studentId)
    {
        return $this->model
            ->where('student_id', $studentId)
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getSecretaryGifts($secretaryId)
    {
        return $this->model
            ->where('secretary_id', $secretaryId)
            ->with(['secretary'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function all()
    {
        return $this->model
            ->with(['student', 'secretary'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function find($id)
    {
        return $this->model
            ->with(['student', 'secretary'])
            ->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $gift = $this->model->findOrFail($id);
        $gift->update($data);
        return $gift;
    }

    public function delete($id)
    {
        return $this->model->findOrFail($id)->delete();
    }
} 