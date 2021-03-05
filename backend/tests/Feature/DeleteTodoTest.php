<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteTodoTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->setBaseRoute('todos');
        $this->setBaseModel('App\Models\Todo');
    }

    /** @test */
    public function a_privilege_user_can_delete_demo()
    {
        $this->signIn();
        $this->destroy()->assertStatus(204);
    }

    /** @test */
    public function an_unauthenticated_user_cannot_delete_a_todo()
    {
        $this->destroy();
    }
}
