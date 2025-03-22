<?php

namespace Tests\Feature\CourseController;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Course;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_create_a_course(): void
    {
        $user = User::factory()->create(['type' => 'teacher']);        

        $course = [
            'title' => fake()->sentence(5),
            'description' => fake()->paragraph(),
            'status' => 'published',
            'published_at' => now()->toDateTimeString()
        ];

        $this->actingAs($user)
            ->postJson('api/courses', $course)
            ->assertCreated()
            ->assertJsonStructure([                
                'id'                
            ]);

        $this->assertDatabaseHas('courses', ['title' => $course["title"]]);
    }

    public function test_student_cannot_create_a_course(): void
    {
        $user = User::factory()->create();

        $course = [
            'title' => fake()->sentence(5),
            'description' => fake()->paragraph(),
            'status' => 'published',
            'published_at' => now()
        ];

        $this->actingAs($user)
            ->postJson('api/courses', $course)
            ->assertForbidden();
    }

    public function test_guest_cant_create_course(): void
    {
        $course = [
            'title' => fake()->sentence(5),
            'description' => fake()->paragraph(),
            'status' => 'published',
            'published_at' => now()
        ];

        $this->postJson('api/courses', $course)
            ->assertUnauthorized();
    }
}