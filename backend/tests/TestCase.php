<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected $base_route = null;
    protected $base_model = null;

    protected function signIn($user = null)
    {
        $user = $user ?? create(\App\Models\User::class);
        $this->actingAs($user);
        return $this;
    }

    protected function setBaseRoute($route)
    {
        $this->base_route = $route;
    }

    protected function setBaseModel($model)
    {
        $this->base_model = $model;
    }

    protected function create($attributes = [], $model = '', $route = '')
    {
        $this->withoutExceptionHandling();

        $route = $this->base_route ? "{$this->base_route}.store" : $route;
        $model = $this->base_model ?? $model;

        if (!auth()->user()) {
            $response = $this->postJson(route($route), [])->getContent();
            $this->assertEquals($response, '{"error":"Unauthorized"}');
            return $response;
        }

        $attributes = raw($model, $attributes);

        $response = $this->postJson(route($route), $attributes)->assertSuccessful();

        $model = new $model;

        $this->assertDatabaseHas($model->getTable(), $attributes);

        return $response;
    }

    protected function update($attributes = [], $model = '', $route = '')
    {
        $this->withoutExceptionHandling();

        $route = $this->base_route ? "{$this->base_route}.update" : $route;
        $model = $this->base_model ?? $model;

        if (!auth()->user()) {
            $response = $this->putJson(route($route, 1), [])->getContent();
            $this->assertEquals($response, '{"error":"Unauthorized"}');
            return $response;
        }

        $model = create($model);

        $response = $this->putJson(route($route, $model->id), $attributes);

        tap($model->fresh(), function ($model) use ($attributes) {
            collect($attributes)->each(function ($value, $key) use ($model) {
                $this->assertEquals($value, $model[$key]);
            });
        });

        return $response;
    }

    protected function destroy($model = '', $route = '')
    {
        $this->withoutExceptionHandling();

        $route = $this->base_route ? "{$this->base_route}.destroy" : $route;
        $model = $this->base_model ?? $model;

        if (!auth()->user()) {
            $response = $this->deleteJson(route($route, 1))->getContent();
            $this->assertEquals($response, '{"error":"Unauthorized"}');
            return $response;
        }

        $model = create($model);

        $response = $this->deleteJson(route($route, $model->id));

        $this->assertDatabaseMissing($model->getTable(), $model->toArray());

        return $response;
    }
}

function create($class, $attributes = [], $times = null)
{
    return $class::factory()->count($times)->create($attributes);
}
function make($class, $attributes = [], $times = null)
{
    return $class::factory()->count($times)->make($attributes);
}
function raw($class, $attributes = [], $times = null)
{
    return $class::factory()->count($times)->raw($attributes);
}
