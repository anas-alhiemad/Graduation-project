<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Trainer;
use App\Models\Section_File;
use App\Models\CourseSection;
use Illuminate\Auth\Access\Response;

class FilePolicy
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
    public function view($user, CourseSection $courseSection)
    {
        $section = $courseSection;
        if ($user instanceof \App\Models\Trainer || $user instanceof \App\Models\Student) {
        return $user->sections()->where('course_sections.id', $section->id)->exists()
            ? Response::allow()
            : Response::deny('You are not allowed to view this file.');
    }

    return Response::deny('Unauthorized user type.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function upload(Trainer $trainer,CourseSection $section)
    {
        return $trainer->sections()->where('course_sections.id', $section->id)->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin, Section_File $sectionFile)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $admin, Section_File $sectionFile)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Admin $admin, Section_File $sectionFile)
    {
        //
    }
}
