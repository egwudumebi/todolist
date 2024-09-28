<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    use HasFactory;
    protected $table = 'reminders';
    protected $fillable = ['task_id', 'reminder_time', 'status',];
    public function casts(): array
    {
        return [
            'reminder_time' => 'datetime',
            'status' => 'boolean',
        ];
    }

    // Relationship with the Task model
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
