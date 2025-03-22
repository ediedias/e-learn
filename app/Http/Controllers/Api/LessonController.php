<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\LessonResource;
use App\Http\Requests\StoreLessonRequest;

class LessonController extends Controller
{
    public function store(StoreLessonRequest $request, Course $course)
    {
        Gate::authorize('create', [Lesson::class, $course]);

        $lesson = $course->lessons()->create($request->validated());

        return response()->json(LessonResource::make($lesson), Response::HTTP_CREATED);
    }
}
