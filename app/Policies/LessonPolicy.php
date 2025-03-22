<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Course;

class LessonPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user, Course $course): bool
    {
        return $user->type === 'teacher' && $user->id === $course->user_id;
    }
}
