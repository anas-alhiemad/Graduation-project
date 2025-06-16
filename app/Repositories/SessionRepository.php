<?php

namespace App\Repositories;

use App\Models\Session;

class SessionRepository
{
    protected $model;

    public function __construct(Session $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getBySection($sectionId, $perPage = 10)
    {
        return $this->model
            ->where('course_section_id', $sectionId)
            ->orderBy('session_date', 'desc')
            ->paginate($perPage);
    }

    public function update($id, array $data)
    {
        $session = $this->getById($id);
        if ($session) {
            $session->update($data);
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $session = $this->getById($id);
        if ($session) {
            return $session->delete();
        }
        return false;
    }
} 