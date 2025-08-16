<?php

namespace App\Services\StudentServices;

use App\Repositories\StudentStatisticsRepository;

class StudentStatisticsService
{
    protected $studentStatisticsRepository;

    public function __construct(StudentStatisticsRepository $studentStatisticsRepository)
    {
        $this->studentStatisticsRepository = $studentStatisticsRepository;
    }

    public function getMonthlyRegistrations($year = null)
    {
        $data = $this->studentStatisticsRepository->getMonthlyRegistrations($year);

        
        $data->transform(function ($item) {
            $item->month_name = date('F', mktime(0, 0, 0, $item->month, 1));
            return $item;
        });

        return $data;
    }

    public function getYearlyRegistrations()
    {
        return $this->studentStatisticsRepository->getYearlyRegistrations();
    }
}
