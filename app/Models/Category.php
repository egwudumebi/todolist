<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    protected $table = 'categories';
    
    public function casts(): array{
        return [
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
        ];
    }

    // Relationship with the Task model
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'category_id');
    }
}
