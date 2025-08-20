<?php

namespace App\Services;

use App\Models\ToDo;
use App\Enums\StatusEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class ToDoService
{
    public function get(): ?array
    {
        try {
            return ToDo::whereNull('end_date')->get()->toArray();
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * @param array $data
     * @return array
     * 
     * Create a todo item in list
     */
    public function create(array $data): array
    {
        try {
            $data['to_do_status_id'] = StatusEnum::Active->value;

            $toDo = ToDo::create($data);

            return [
                "successful" => true,
                "message" => "To do created successfully!",
                "data" => $toDo,
            ];
        } catch (QueryException $e) {
            Log::error("QueryException creating Todo", ["error" => $e->getMessage()]);

            return [
                "successful" => false,
                "message" => "Database error while creating Todo.",
                "error" => $e->getMessage(),
            ];
        } catch (\Exception $e) {
            Log::error("Exception creating Todo", ["error" => $e->getMessage()]);

            return [
                "successful" => false,
                "message" => "Unexpected error while creating Todo.",
                "error" => $e->getMessage(),
            ];
        }
    }

    /**
     * @param int $todoItem
     * @return array
     * 
     * Change todo item status to completed
     */
    public function complete(int $todoItem): array
    {
        try {
            $todo = ToDo::where('id', $todoItem);
            if (!$todo) {
                return [
                    "successful" => false,
                    "message" => "Todo not found."
                ];
            }

            $todo->update([
                "to_do_status_id" => StatusEnum::Complete->value
            ]);
            
            return [
                "success" => true,
                "message" => "Todo item completed!"
            ];
        } catch (QueryException $e) {
            Log::error("QueryException completing Todo", ["error" => $e->getMessage()]);

            return [
                "successful" => false,
                "message" => "Database error while completing Todo.",
                "error" => $e->getMessage(),
            ];
        } catch (\Exception $e) {
            Log::error("Exception completing Todo", ["error" => $e->getMessage()]);

            return [
                "successful" => false,
                "message" => "Unexpected error while creating Todo.",
                "error" => $e->getMessage(),
            ];
        }
    }

    /**
     * @param int $todoItem
     * @return array
     * 
     * Deletes the todo item from the list
     */
    public function delete(int $todoItem): array
    {
        try {
            $todo = ToDo::where('id', $todoItem)
                        ->whereNull('end_date')
                        ->first();

            if (!$todo) {
                return [
                    "successful" => false,
                    "message" => "Todo not found."
                ];
            }

            // Soft delete todo item for archiving purposes
            $todo->update([
                'to_do_status_id' => 3,
                'end_date' => now()
            ]);
            
            return [
                "successful" => true,
                "message" => "Todo deleted successfully."
            ];

        } catch (\Exception $e) {
            return [
                "successful" => false,
                "message" => "Error deleting todo: " . $e->getMessage()
            ];
        }
    }
}