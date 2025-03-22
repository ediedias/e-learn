<?php

namespace Tests\Feature\CourseController;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Course;

class DestroyTest extends TestCase
{
     use RefreshDatabase;

     public function test_teacher_can_delete_their_own_course(): void
     {
          $teacher = User::factory()->create(['type' => 'teacher']);

          $course = Course::factory()->for($teacher, 'teacher')->create();          

          $this->actingAs($teacher)
               ->deleteJson('api/course/' . $course->id)
               ->assertOk();

          $this->assertDatabaseMissing('courses', ['id' => $course->id]);
     }

     public function test_teacher_cant_delete_not_owned_courses(): void
     {
          $teacher = User::factory()->create(['type' => 'teacher']);

          $course = Course::factory()->create(['user_id' => 100]);

          $this->actingAs($teacher)
               ->deleteJson('api/course/' . $course->id)
               ->assertForbidden();

          $this->assertDatabaseHas('courses', ['id' => $course->id]);
     }

     public function test_student_cant_delete_course(): void
     {
          $student = User::factory()->create();

          $course = Course::factory()->create();

          $this->actingAs($student)
               ->deleteJson('api/course/' . $course->id)
               ->assertForbidden();

          $this->assertDatabaseHas('courses', ['id' => $course->id]);
     }
}
