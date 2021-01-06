<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TodoResource;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $todos = Todo::where('user_id', $this->user->id)->get();
        return response(['todos' => TodoResource::collection($todos), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->post();
        $data['user_id'] = $this->user->id;

        $validator = Validator::make($data, [
            'description' => 'required|max:255',
            'status' => 'max:1',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $todo = Todo::create($data);

        return response(['todo' => new TodoResource($todo), 'message' => 'Created successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        //
        if ($todo->user_id != $this->user->id) {
            return response(['error' => 'No permission'], 401);
        }
        return response(['todo' => new TodoResource($todo), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        //
        $data = $request->post();
        if ($this->user->id != $todo->user_id) {
            return response(['error' => 'No permission'], 401);
        }
        $validator = Validator::make($data, [
            'description' => 'max:255',
            'status' => 'max:1',
        ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $todo->update($data);
        return response(['todo' => new TodoResource($todo), 'message' => 'Update successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        //
        if ($this->user->id != $todo->user_id) {
            return response(['error' => 'No permission'], 401);
        }
        $todo->delete();
        return response(['message' => 'Deleted'], 204);
    }
}
