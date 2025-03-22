<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Course;

class CoursePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user): bool
    {
        return $user->type === 'teacher';
    }

    public function update(User $user, Course $course): bool
    {
        return $user->type === 'teacher' && $user->id === $course->user_id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->type === 'teacher' && $user->id === $course->user_id;
    }
}
