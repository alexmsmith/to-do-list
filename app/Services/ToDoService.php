<?php

namespace App\Services;

use App\Models\ToDo;
use App\Enums\StatusEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class ToDoService
{
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
            Log::error("QueryException creating ToDo", ["error" => $e->getMessage()]);

            return [
                "successful" => false,
                "message" => "Database error while creating ToDo.",
                "error" => $e->getMessage(),
            ];
        } catch (\Exception $e) {
            Log::error("Exception creating ToDo", ["error" => $e->getMessage()]);

            return [
                "successful" => false,
                "message" => "Unexpected error while creating ToDo.",
                "error" => $e->getMessage(),
            ];
        }
    }
}