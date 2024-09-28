<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('due_date');
            $table->enum('priority', ['low', 'medium', 'high'])->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');  // Adds user_id column, sets constraint
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');  // Adds category_id, constraint with onDelete
            $table->datetime('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
