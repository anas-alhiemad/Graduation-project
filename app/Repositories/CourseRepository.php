<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;

class CourseRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    public function search($search)
    {
        return $this->model::query()
            ->where('name', 'LIKE', "%{$search}%")
            ->with('department')
            ->paginate(10);
    }

    public function getById($id)
    {
        return $this->model::with('department')->findOrFail($id);
    }

    public function getAll()
    {
        return $this->model::with('department')->paginate(10);
    }

    public function create(array $data)
    {
        return $this->model::create($data);
    }

    public function update($id, array $data)
    {
        $course = $this->getById($id);
        $course->update($data);
        return $course;
    }

    public function delete($id)
    {
        return $this->model::destroy($id);
    }
  public function getByDepartment($departmentId)
{
    return $this->model::with('department')
        ->where('department_id', $departmentId)
        ->paginate(10); 
}
public function getCoursesByDepartmentIds($departmentIds)
{
    return $this->model::whereIn('department_id', $departmentIds)->get();
}

} 