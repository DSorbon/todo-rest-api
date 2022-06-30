<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        try {
            $tasks = $request->user()->tasks;
            return response()->json($tasks, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    public function show($id)
    {
        try {
            $task =  Task::find($id)->first();

            if (!$task) {
                return response()->json(['message' => 'Task not found'], 404);
            }

            $response = [
                'message' => 'Task founded successfully',
                'task' => $task
            ];

            return response()->json($response, 200);
            
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
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

            $response = [
                'message' => 'Task created successfully',
                'task' => $task
            ];

            return response()->json($response, 201);

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $fields = $request->validate([
                'name' => ['required', 'string', 'max:100'],
            ]);

            $task = Task::find($id);

            $task->update($fields);

            $response = [
                'message' => 'Task updated successfully',
                'task' => $task
            ];

            return response()->json($response);
   
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $task = Task::find($id)->first();
            $task->delete();

            return response()->json(['message' => 'Task updated successfully'], 204);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }
}
