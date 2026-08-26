<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained('users')->restrictOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced'])->default('intermediate');
            $table->string('goal')->nullable(); // strength, hypertrophy, endurance, weight_loss, general
            $table->boolean('is_template')->default(false);
            $table->integer('duration_weeks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('trainer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routines');
    }
};
