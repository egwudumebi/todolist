<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditTaskRequest;
use App\Http\Requests\TaskFormRequest;
use App\Models\Reminder;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all tasks
        $tasks = Task::all();
        // Return the tasks as a JSON response (ideal for APIs)
        return response()->json($tasks);
    }

    /**
     * Display a listing of the user's registerd tasks.
    */
    public function getUserTasks($user_id)
    {
        // Find the user by ID
        $user = User::find($user_id);

        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        // Fetch all tasks for the user using the relationship
        $tasks = $user->tasks()->get();  // or $user->tasks;
        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'User has not Tasks']);
        }

        // Return tasks as JSON
        return response()->json($tasks, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskFormRequest $request)
    {
        // If validation fails, Laravel automatically handles it and returns a JSON response with errors.
        try{
            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'priority' => $request->priority,
                'status' => 'pending',
                'user_id' => $request->user_id,
                'category_id' => $request->category_id,
            ]);
            if ($task) {
                // Set a remonder for this task
                $reminder = Reminder::create([
                    'task_id' => $task->id,
                    'reminder_time' => $task->due_date,
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'Task created successfully',
                    'task' => $task,
                    'reminder' => $reminder,
                ]);
            }
        } catch (\Exception $e) {
            // Handle foreign key violation
            return response()->json([
                'status' => 400,
                'message' => 'Invalid user ID or category ID.',
                'error' => $e->getMessage(), // Optional: include error details
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Retrieve the selectded task
        $task = Task::find($id);
        if($task) {
            return response()->json([
                'task' => $task,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditTaskRequest $request, string $id)
    {
        try {
            $task = Task::find($id); // Throws ModelNotFoundException if not found
            
            // Check if the Task exists
            if($task) {
                // Update fields using the request data
                $task->update([
                    'title' => $request->title ?: $task->title,  // Ensure this is the correct field name
                    'description' => $request->description ?: $task->description,
                    'due_date' => $request->due_date ?: $task->due_date,
                    'priority' => $request->priority ?: $task->priority,
                    'status' => $request->status ?: $task->status,
                    'completed_at' => $request->status == 'completed' && $task->status != 'completed' ? Carbon::now() : $task->completed_at,
                    // 'updated_at' => Carbon::now(), update handles itself automatically
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Task updated successfully',
                    'task' => $task,
                ]);
            }

            // Task doesn't exist
            return response()->json([
                'status' => 404,
                'message' => 'Task does not exist',
            ]);
            

        } catch (\Exception $e) {
            // Handle any other exception
            return response()->json([
                'status' => 500,
                'message' => 'Operation Failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        if($task) {
            $task->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Task Deleted Successfully',
                'task' => $task,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Task not Found',
            ]);
        }
    }
}
