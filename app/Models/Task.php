<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
    use HasFactory;
    protected $table = "tasks";
    protected $fillable = [
        'title', 
        'description', 
        'due_date', 
        'priority',
        'status',
        'user_id',
        'category_id',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
            'due_date' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    // Establish relationship with the User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with the Reminder Model
    public function reminder(): HasOne
    {
        return $this->hasOne(Reminder::class, 'task_id');
    }

    // Relationship with the Category model
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
