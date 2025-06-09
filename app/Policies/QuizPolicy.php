<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\Admin;
use App\Models\Trainer;
use App\Models\CourseSection;
use Illuminate\Auth\Access\Response;

class QuizPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $admin) 
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Admin $admin, Quiz $quiz) 
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Trainer $trainer, CourseSection $section) 
    {
        return $trainer->sections()->where('course_sections.id', $section->id)->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $admin, Quiz $quiz) 
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin, Quiz $quiz) 
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $admin, Quiz $quiz) 
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Admin $admin, Quiz $quiz) 
    {
        //
    }
}
