<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ToDoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ToDoController extends Controller
{
    protected ToDoService $todoService;

    public function __construct(ToDoService $todoService)
    {
        $this->todoService = $todoService;
    }

    /**
     * @param Request $request
     * @return array
     * 
     * Validate data from request
     */
    public function validateData(Request $request): array
    {
        return $request->validate([
            'task_description' => 'required|string|max:255',
        ]);
    }

    /**
     * @return JsonResponse
     * 
     * Return all todo items for list
     */
    public function index(): JsonResponse
    {
        try {
            $todoItems = $this->todoService->get();
            return response()->json($todoItems);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 404);
        }
    }

    /**
     * @param Request $request
     * @return JsonReponse
     * 
     * Create a new todo item in the list
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $validateData = $this->validateData($request);
            $response = $this->todoService->create($validateData);

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 404);
        }
    }

    /**
     * @param int $todoId
     * @return JsonResponse
     * 
     * Completes a todo item in the list
     */
    public function complete(int $todoId): JsonResponse
    {
        try {
            $response = $this->todoService->complete($todoId);

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 404);
        }
    }

    /**
     * @param int $todoId
     * @return JsonResponse
     * 
     * Deletes a todo item in the list
     */
    public function delete(int $todoId): JsonResponse
    {
        try {
            $response = $this->todoService->delete($todoId);

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage], 404);
        }
    }
}
