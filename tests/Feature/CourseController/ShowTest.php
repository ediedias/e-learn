<?php

namespace Tests\Feature\CourseController;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Course;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_a_single_course(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();

        $this->actingAs($user)
            ->getJson('api/course/' . $course->id)
            ->assertOk()
            ->assertJsonFragment([
                'type' => 'course',
                'id' => $course->id                
            ]);
    }

    public function test_returns_404_if_course_doesnt_exist(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();

        $this->actingAs($user)
            ->getJson('api/course/100')
            ->assertNotFound();
    }
}