<?php

namespace App\Http\Services;

use App\Http\Resources\SubTaskResource;
use App\Models\SubTask;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SubTaskService 
{
    public static function findAllByTaskId($task_id)
    {
        $subTasks = new SubTaskResource(SubTask::all()->where('task_id', '=', $task_id));

        if (!$subTasks) {
            throw new ModelNotFoundException('SubTasks not found');
        }

        return $subTasks;
    }

    public static function findById($id, $task_id)
    {
        $subTask = SubTask::where('task_id', '=', $task_id)->find($id);

        if (!$subTask) {
            throw new ModelNotFoundException('SubTask not found');
        }

        return $subTask;
    }

}