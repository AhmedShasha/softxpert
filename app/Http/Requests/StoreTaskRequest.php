<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'assigned_user_id' => 'required|exists:users,id',
            'status' => 'nullable|integer|in:' . implode(',', array_column(TaskStatus::all(), 'value')),
            'dependencies' => 'array',
            'dependencies.*' => 'integer|exists:tasks,id',
        ];
    }
}
