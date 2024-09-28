<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditTaskRequest;
use App\Http\Requests\TaskFormRequest;
use App\Models\Task;
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
    
            return response()->json([
                'status' => 200,
                'message' => 'Task created successfully',
                'task' => $task,
            ]);
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
