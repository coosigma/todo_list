<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UpdateTodoTest extends TestCase
{
    private $attributes = [];
    public function setUp(): void
    {
        parent::setUp();
        $this->setBaseRoute('todos');
        $this->setBaseModel('App\Models\Todo');

        $this->attributes = [
            'description' => Str::random(12),
            'status' => random_int(0, 1),
        ];
    }

    /** @test */
    public function a_user_can_update_a_todo()
    {
        $this->signIn();
        $user_id = Auth::user()->id;
        $this->attributes['user_id'] = $user_id;
        $this->update($this->attributes);
    }

    /** @test */
    public function an_unauthenticated_user_cannot_update_a_todo()
    {
        $this->update();
    }
}
