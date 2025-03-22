<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    public function index()
    {
        return CourseResource::collection(Course::with('teacher')->latest()->paginate(10));        
    }

    public function show(Request $request, Course $course)
    {
        return new CourseResource($course);
    }

    public function store(StoreCourseRequest $request)
    {
        Gate::authorize('create', Course::class);        

        $course = $request->user()->courses()->create($request->validated());

        return response()->json(CourseResource::make($course), Response::HTTP_CREATED);
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        Gate::authorize('update', $course);

        $course->update($request->validated());

        return response()->json(CourseResource::make($course), Response::HTTP_OK);
    }

    public function destroy(Course $course)
    {
        Gate::authorize('delete', $course);

        $course->delete();

        return response()->json(["message" => "Course deleted"], Response::HTTP_OK);
    }
}
