<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'assigned_user_id',
    ];

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function dependencies(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'dependencies', 'task_id', 'dependence_id')->withTimestamps();
    }

    // scopes for filters
    public function scopeFilterByStatus($query, $status)
    {
        return $query->when($status, fn($q) => $q->where('status', $status));
    }

    public function scopeFilterByAssignedUser($query, $userId)
    {
        return $query->when($userId, fn($q) => $q->where('assigned_user_id', $userId));
    }

    public function scopeFilterByDateRange($query, $from, $to)
    {
        return $query
            ->when($from, fn($q) => $q->whereDate('due_date', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('due_date', '<=', $to));
    }
}
