<?php

namespace App\Http\Controllers;

use App\Exceptions\ForbiddenException;
use App\Exceptions\TaskNotFoundException;
use App\Http\Services\SubTaskService;
use App\Http\Services\TaskService;
use App\Models\SubTask;
use Error;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SubTaskController extends Controller
{
    public function index(Request $request, $task_id)
    {
        try {
            $task = TaskService::findById($task_id);

            $user = $request->user();

            $ownerCorrect = TaskService::ownerCorrect($user->id, $task->id);

            if (!$ownerCorrect) {
                throw new ForbiddenException('You don\'t have permission');
            }

            $subTasks = SubTaskService::findAllByTaskId($task->id);

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
            $task = TaskService::findById($task_id);

            $user = $request->user();

            $ownerCorrect = TaskService::ownerCorrect($user->id, $task->id);

            if (!$ownerCorrect) {
                throw new ForbiddenException('You don\'t have permission');
            }

            $subTask = SubTaskService::findById($id, $task->id);

            $response = [
                'message' => 'SubTask founded successfully',
                'task' => $subTask
            ];

            return response()->json($response, 200);
            
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (ForbiddenException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function store(Request $request, $task_id)
    {
        try {
            $task = TaskService::findById($task_id);

            $user = $request->user();

            $ownerCorrect = TaskService::ownerCorrect($user->id, $task->id);

            if (!$ownerCorrect) {
                throw new ForbiddenException('You don\'t have permission');
            }

            $fields = $request->validate([
                'title' => ['required', 'string', 'max:70'],
                'description' => ['required', 'string']
            ]);

            $fields['task_id'] = $task->id;

            $subTask = SubTask::create($fields);

            $response = [
                'message' => 'SubTask created successfully',
                'subTask' => $subTask
            ];

            return response()->json($response, 201);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (ForbiddenException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function update(Request $request, $task_id, $id)
    {
        try {
            $task = TaskService::findById($task_id);

            $user = $request->user();

            $ownerCorrect = TaskService::ownerCorrect($user->id, $task->id);

            if (!$ownerCorrect) {
                throw new ForbiddenException('You don\'t have permission');
            }

            $subTask = SubTaskService::findById($id, $task->id);

            $fields = $request->validate([
                'title' => ['string', 'max:70'],
                'description' => ['string']
            ]);

            $subTask->update($fields);

            $response = [
                'message' => 'Task updated successfully',
                'subTask' => $subTask
            ];

            return response()->json($response);
   
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (ForbiddenException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function delete(Request $request, $task_id, $id)
    {
        try {
            $task = TaskService::findById($task_id);

            $user = $request->user();

            $ownerCorrect = TaskService::ownerCorrect($user->id, $task->id);

            if (!$ownerCorrect) {
                throw new ForbiddenException('You don\'t have permission');
            }

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
