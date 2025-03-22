<?php

namespace Tests\Feature\LessonController;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Course;

class StoreTest extends TestCase
{
     use RefreshDatabase;

     public function test_teacher_can_create_a_lesson_in_own_course(): void
     {
          $teacher = User::factory()->create(['type' => 'teacher']);
          $course = Course::factory()->for($teacher, 'teacher')->create();          

          $lesson = [
               'title' => fake()->sentence(5),
               'content' => fake()->paragraph()
          ];

          $this->actingAs($teacher)
               ->postJson('api/course/' . $course->id . '/lessons', $lesson)
               ->assertCreated()
               ->assertJsonStructure([
                    'id'
               ]);

          $this->assertDatabaseHas('lessons', ['title' => $lesson["title"]]);
     }

     public function test_teacher_cannot_create_a_lesson_in_other_course(): void
     {
          $teacher = User::factory()->create(['type' => 'teacher']);
          $course = Course::factory()->create(['user_id' => 100]);

          $lesson = [
               'title' => fake()->sentence(5),
               'content' => fake()->paragraph()
          ];

          $this->actingAs($teacher)
               ->postJson('api/course/' . $course->id . '/lessons', $lesson)
               ->assertForbidden();

          $this->assertDatabaseMissing('lessons', ['title' => $lesson["title"]]);
     }

     public function test_student_cant_create_lesson(): void
     {
          $student = User::factory()->create();
          $course = Course::factory()->create();

          $lesson = [
               'title' => fake()->sentence(5),
               'content' => fake()->paragraph()
          ];

          $this->actingAs($student)
               ->postJson('api/course/' . $course->id . '/lessons', $lesson)
               ->assertForbidden();

          $this->assertDatabaseMissing('lessons', ['title' => $lesson["title"]]);
     }
}
