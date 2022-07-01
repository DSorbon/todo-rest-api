<?php

namespace App\Http\Services;

use App\Exceptions\ForbiddenException;
use App\Exceptions\TaskNotFoundException;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskService
{
    public static function findById($id)
    {
        $task = Task::find($id);

        if (!$task) {
            throw new ModelNotFoundException('Task not found by id ' . $id);
        }

        return $task;
    }

    public static function ownerCorrect($owner_id, $task_id)
    {
        $task = User::find($owner_id)->tasks->where('id', '=', $task_id)->first();

        if (!$task) {
            return false;
        }

        return true;
    }
}