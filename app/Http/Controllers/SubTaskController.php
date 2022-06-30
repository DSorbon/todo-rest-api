<?php

namespace App\Http\Controllers;

use App\Models\SubTask;
use Illuminate\Http\Request;

class SubTaskController extends Controller
{
    public function index(Request $request, $task_id)
    {
        try {
            $task = $request->user()->tasks->where('id', '=', $task_id)->first();

            $subTasks = SubTask::all()->where('task_id', '=', $task->id);
            return response()->json($subTasks, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    public function show(Request $request, $task_id, $id)
    {
        try {
            $task = $request->user()->tasks->where('id', '=', $task_id)->first();

            $subTasks = SubTask::find($id)->where('task_id', '=', $task->id)->first();


            if (!$subTasks) {
                return response()->json(['message' => 'SubTask not found'], 404);
            }

            $response = [
                'message' => 'SubTask founded successfully',
                'task' => $subTasks
            ];

            return response()->json($response, 200);
            
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    public function store(Request $request, $task_id)
    {
        try {
            $task = $request->user()->tasks->where('id', '=', $task_id)->first();

            if (!$task) {
                return response()->json(['message' => 'Task not found'], 404);
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

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    public function update(Request $request, $task_id, $id)
    {
        try {
            $task = $request->user()->tasks->where('id', '=', $task_id)->first();

            $subTasks = SubTask::find($id)->where('task_id', '=', $task->id)->first();

            if (!$task) {
                return response()->json(['message' => 'Task not found'], 404);
            }

            if (!$subTasks) {
                return response()->json(['message' => 'SubTask not found'], 404);
            }

            $fields = $request->validate([
                'title' => ['required', 'string', 'max:70'],
                'description' => ['required', 'string']
            ]);

            $subTasks->update($fields);

            $response = [
                'message' => 'Task updated successfully',
                'subTask' => $subTasks
            ];

            return response()->json($response);
   
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    public function delete(Request $request, $task_id, $id)
    {
        try {
            $task = $request->user()->tasks->where('id', '=', $task_id)->first();

            $subTasks = SubTask::find($id)->where('task_id', '=', $task->id)->first();

            if (!$task) {
                return response()->json(['message' => 'Task not found'], 404);
            }

            if (!$subTasks) {
                return response()->json(['message' => 'SubTask not found'], 404);
            }

            $subTasks->delete();

            return response()->json(['message' => 'SubTask has been deleted'], 204);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }
}
