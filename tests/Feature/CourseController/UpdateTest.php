<?php

namespace Tests\Feature\CourseController;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Course;

class UpdateTest extends TestCase
{
     use RefreshDatabase;

     public function test_teacher_can_update_their_own_course(): void
     {
          $teacher = User::factory()->create(['type' => 'teacher']);

          $course = Course::factory()->for($teacher, 'teacher')->create();

          $courseUpdate = [
               'title' => 'Title updated'
          ];

          $this->actingAs($teacher)
               ->patchJson('api/course/' . $course->id, $courseUpdate)
               ->assertOk()
               ->assertJsonFragment([
                    'title' => 'Title updated'
               ]);

          $this->assertDatabaseHas('courses', ['title' => 'Title updated']);
     }

     public function test_teacher_cant_update_not_owned_courses(): void
     {
          $teacher = User::factory()->create(['type' => 'teacher']);

          $course = Course::factory()->create(['user_id' => 100]);

          $courseUpdate = [
               'title' => 'Title updated'
          ];

          $this->actingAs($teacher)
               ->patchJson('api/course/' . $course->id, $courseUpdate)
               ->assertForbidden();

          $this->assertDatabaseMissing('courses', ['title' => 'Title updated']);
     }

     public function test_student_cant_update_course(): void
     {
          $student = User::factory()->create();

          $course = Course::factory()->create();

          $courseUpdate = [
               'title' => 'Title updated'
          ];

          $this->actingAs($student)
               ->patchJson('api/course/' . $course->id, $courseUpdate)
               ->assertForbidden();

          $this->assertDatabaseMissing('courses', ['title' => 'Title updated']);
     }
}
