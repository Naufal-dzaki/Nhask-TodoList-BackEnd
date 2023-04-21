<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\Task;
use App\Models\Status;
use App\Models\User;

class TaskController extends Controller
{
    public function index() {
        $task = Task::where('user_id', auth()->user()->id)->get();

        return response()->json([
            "message" => "successfully fetched user",
            "data" => $task
        ], Response::HTTP_OK);
    }

    public function show($id) {
        $task = Task::where('user_id', auth()->user()->id)->findOrFail($id);

        return response()->json([
            "message" => "successfully fetched task",
            "data" => $task
        ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            "title" => "required|string",
            "level" => "required|numeric",
            "description" => "required|string",
            "deadline" => "required|date",
            "user_id" => "nullable",
            "status_id" => "nullable"
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => "failed creating a new task.",
                "errors" => $validator->errors()
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $validated = $validator->validated();
        $validated["user_id"] = auth()->user()->id; //set task user_id depending on authenticated user
        $validated["status_id"] = 1; //set task status to "to-do"

        try {
            $createdTask = Task::create($validated);
        } catch (\Excaption $e) {
            return response()->json([
                "message" => "Failed creating a new task.",
                "error" => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            "message" => "successfully created new task",
            "data" => $createdTask
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            "title" => "string",
            "deadline" => "date",
            "level" => "numeric",
            "description" => "string",
            "user_id" => "nullable",
            "status_id" => "nullable"
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => "failed creating a new task.",
                "errors" => $validator->errors()
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $validated = $validator->validated();

        try {
            $updatedTask = Task::findOrFail($id);
            $updatedTask->update($validated);
        } catch (\Excaption $e) {
            return response()->json([
                "message" => "Failed creating a new task.",
                "error" => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            "message" => "successfully updated task",
            "data" => $updatedTask
        ]);
    }

    public function destroy($id) {
        $task = Task::where('user_id', auth()->user()->id)->findOrFail($id);

        try {
            $deletedTask = $task->delete($task);
        } catch (\Excaption $e) {
            return response()->json([
                "message" => "Failed deleted task.",
                "error" => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            "message" => "successfully deleted a task",
            "data" => $task
        ], Response::HTTP_OK);
    }
}
