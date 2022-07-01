<?php

namespace App\Http\Controllers;

use App\Exceptions\ForbiddenException;
use App\Http\Resources\SubTaskResource;
use App\Http\Services\SubTaskService;
use App\Http\Services\TaskService;
use App\Models\SubTask;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SubTaskController extends Controller
{
    public function index(Request $request, $task_id)
    {
        try {
            $task = TaskService::ownerCorrect($request, $task_id);

            $subTasks = SubTaskResource::collection(SubTaskService::findAllByTaskId($task->id));

            return response()->json($subTasks, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (ForbiddenException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function show(Request $request, $task_id, $id)
    {
        try {
            $task = TaskService::ownerCorrect($request, $task_id);

            $subTask = SubTaskService::findById($id, $task->id);

            return response()->json(new SubTaskResource($subTask), 200);
            
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (ForbiddenException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function store(Request $request, $task_id)
    {
        try {
            $task = TaskService::ownerCorrect($request, $task_id);

            $fields = $request->validate([
                'title' => ['required', 'string', 'max:70'],
                'description' => ['required', 'string']
            ]);

            $fields['task_id'] = $task->id;

            $subTask = SubTask::create($fields);

            return response()->json(new SubTaskResource($subTask), 201);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (ForbiddenException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function update(Request $request, $task_id, $id)
    {
        try {
            $task = TaskService::ownerCorrect($request, $task_id);

            $subTask = SubTaskService::findById($id, $task->id);

            $fields = $request->validate([
                'title' => ['string', 'max:70'],
                'description' => ['string']
            ]);

            $subTask->update($fields);

            return response()->json(new SubTaskResource($subTask));
   
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (ForbiddenException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function delete(Request $request, $task_id, $id)
    {
        try {
            $task = TaskService::ownerCorrect($request, $task_id);

            $subTask = SubTaskService::findById($id, $task->id);

            $subTask->delete();

            return response()->json(['message' => 'SubTask has been deleted'], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (ForbiddenException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }
}
