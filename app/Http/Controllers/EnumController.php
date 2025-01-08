<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Enums\UserRole;

class EnumController extends Controller
{
    public function getEnums()
    {
        $enumClasses = [
            'user_role' => UserRole::all(),
            'task_status' => TaskStatus::all(),
        ];

        return response()->json($enumClasses);
    }
}
