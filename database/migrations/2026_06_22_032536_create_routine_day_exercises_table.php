<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routine_day_exercises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('routine_day_id')->index();
            $table->unsignedBigInteger('exercise_id')->index();
            $table->tinyInteger('sets')->default(3);
            $table->string('reps', 20)->default('10'); // "10", "8-12", "to failure"
            $table->decimal('weight_kg', 6, 2)->nullable();
            $table->integer('rest_seconds')->default(60);
            $table->text('notes')->nullable();
            $table->tinyInteger('order')->default(0);
            $table->timestamps();

            $table->index(['routine_day_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routine_day_exercises');
    }
};
