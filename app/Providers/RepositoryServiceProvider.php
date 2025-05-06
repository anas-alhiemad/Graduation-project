<?php

namespace App\Providers;

use App\Repositories\CourseRepository;
use App\Interfaces\RepositoryInterface;
use App\Repositories\StudentRepository;
use App\Repositories\TrainerRepository;
use App\Repositories\WeekDayRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\EmployeeRepository;
use App\Repositories\SecretaryRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\CourseSectionRepository;
use App\Repositories\PasswordResetRepository;
use App\Repositories\SectionStudentRepository;
use App\Repositories\SectionTrainerRepository;
use App\Repositories\CourseSectionWeekDayRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(RepositoryInterface::class, StudentRepository::class);
        $this->app->bind(RepositoryInterface::class, SecretaryRepository::class);
        $this->app->bind(RepositoryInterface::class, TrainerRepository::class);
        $this->app->bind(RepositoryInterface::class, WeekDayRepository::class);
        $this->app->bind(RepositoryInterface::class, CourseRepository::class);
        $this->app->bind(RepositoryInterface::class, CourseSectionRepository::class);
        $this->app->bind(RepositoryInterface::class, CourseSectionWeekDayRepository::class);
        $this->app->bind(RepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(RepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(RepositoryInterface::class, PasswordResetRepository::class);
        $this->app->bind(RepositoryInterface::class, SectionStudentRepository::class);
        $this->app->bind(RepositoryInterface::class, SectionTrainerRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
