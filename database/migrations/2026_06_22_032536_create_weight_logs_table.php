<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weight_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->decimal('weight_kg', 8, 2);
            $table->unsignedInteger('reps')->default(1);
            $table->decimal('one_rep_max', 8, 2); // Epley 1RM formula calculation
            $table->date('logged_at');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'exercise_id']);
            $table->index(['exercise_id', 'one_rep_max']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weight_logs');
    }
};
