<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('observations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->text('content');
            $table->boolean('is_private')->default(false); // private = only trainer sees it
            $table->enum('category', ['technique', 'nutrition', 'motivation', 'general', 'injury', 'progress'])
                  ->default('general');
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();

            $table->index(['student_id', 'is_private']);
            $table->index(['student_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observations');
    }
};
