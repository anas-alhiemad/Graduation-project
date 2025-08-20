<?php

namespace App\Repositories;

use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentStatisticsRepository
{
    protected $model;

    public function __construct(Student $model)
    {
        $this->model = $model;
    }
    public function getMonthlyRegistrations($year = null)
    {
        $query = $this->model->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total_students')
            )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month');

        if ($year) {
            $query->whereYear('created_at', $year);
        }

        return $query->get();
    }

    public function getYearlyRegistrations()
    {
        return $this->model->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total_students')
            )
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year')
            ->get();
    }
}
