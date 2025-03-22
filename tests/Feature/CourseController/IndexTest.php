<?php

namespace Tests\Feature\CourseController;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Course;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_the_resource_and_is_paginated(): void
    {
        $user = User::factory()->create();
        Course::factory(12)->create();

        $this->actingAs($user)
            ->getJson('api/courses')
            ->assertOk()
            ->assertJsonFragment([
                'type' => 'course'                
            ])
            ->assertJsonStructure([
                'data',
                'links' => ['first', 'last', 'prev', 'next']
            ]);            
    }
}