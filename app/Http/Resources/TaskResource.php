<?php

namespace App\Http\Resources;

use App\Enums\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description'  => $this->description,
            'status'    => new EnumResource($this->status, TaskStatus::class),
            'due_date'  => $this->due_date,
            'assigned_user' => $this->whenLoaded('assignedUser', fn() => new UserReource($this->assignedUser)),
            'dependencies' => $this->whenLoaded('dependencies', fn() => TaskResource::collection($this->dependencies)),
            'created_at'    => $this->created_at,
        ];
    }
}
