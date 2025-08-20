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
            'task' => 'required|string|max:255',
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
            $todoItems = $this->ToDoService->get();
            return response()->json($todoItems);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 404);
        }
    }

    //todo: validate function

    /**
     * @return JsonResponse
     * 
     * Perform an action, i.e., create/complete/delete todo item
     */
    public function action(string $action, int $todoId, Request $request): JsonResponse
    {
        try {
            switch ($action) {
                case "create":
                    $validateData = $this->validateData($request);
                    $response = $this->ToDoService->store($validateData);
                    break;
                case "complete":
                    $response = $this->ToDoService->complete($todoId);
                    break;
                case "delete":
                    $response = $this->ToDoService->delete($todoId);
                    break;
                default:
                    return response()->json([
                        "message" => "Invalid action."
                    ], 400);
            }

            return response()->json([
                "success" => true,
                "message" => $response["message"]
            ]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 404);
        }
    }
}
