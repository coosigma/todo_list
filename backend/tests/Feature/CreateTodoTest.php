<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTodoTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->setBaseRoute('todos');
        $this->setBaseModel('App\Models\Todo');
    }

    /** @test */
    public function a_user_can_create_a_todo()
    {
        $this->signIn();
        $this->create();
    }

    /** @test */
    public function an_unauthenticated_user_cannot_create_a_todo()
    {
        $this->create();
    }

    /** @test */
    public function a_todo_requires_a_description()
    {
        $this->signIn();
        $response = $this->post(route('todos.store'), [])->getContent();
        $res = json_decode($response, true);
        $this->assertEquals('Validation Error', $res[0]);
        return $response;
    }
}
