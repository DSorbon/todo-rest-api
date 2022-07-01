<?php

namespace App\Http\Services;

use App\Exceptions\ForbiddenException;
use App\Exceptions\TaskNotFoundException;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskService
{
    public static function findById($id)
    {
        $task = new TaskResource(Task::find($id));

        if (!$task) {
            throw new ModelNotFoundException('Task not found by id ' . $id);
        }

        return $task;
    }

    public static function ownerCorrect($request, $task_id)
    {
        $task = $request->user()->tasks->where('id', '=', $task_id)->first();

        if (!$task) {
            throw new ForbiddenException('You don\'t have permission');
        }

        return new TaskResource($task);
    }
}