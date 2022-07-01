<?php

namespace App\Http\Controllers;

use App\Exceptions\ForbiddenException;
use App\Http\Resources\TaskResource;
use App\Http\Services\TaskService;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        try {
            $tasks = TaskResource::collection($request->user()->tasks);
            return response()->json($tasks, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $task = TaskService::ownerCorrect($request, $id);

            return response()->json($task, 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (ForbiddenException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function store(Request $request)
    {
        try {
            $fields = $request->validate([
                'name' => ['required', 'string', 'max:100'],
            ]);

            $fields['user_id'] = $request->user()->id;

            $task = Task::create($fields);

            return response()->json(new TaskResource($task), 201);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $task = TaskService::ownerCorrect($request, $id);

            $fields = $request->validate([
                'name' => ['string', 'max:100'],
            ]);

            $task->update($fields);

            return response()->json(new TaskResource($task));
   
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (ForbiddenException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $task = TaskService::ownerCorrect($request, $id);

            $task->delete();

            return response()->json(['message' => 'Task has been deleted'], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (ForbiddenException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }
}
