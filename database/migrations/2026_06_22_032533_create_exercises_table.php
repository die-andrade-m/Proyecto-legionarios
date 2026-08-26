<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('muscle_group'); // chest, back, legs, shoulders, arms, core, cardio, full_body
            $table->string('secondary_muscle_group')->nullable();
            $table->string('equipment')->nullable(); // barbell, dumbbell, machine, bodyweight, cable
            $table->string('difficulty')->default('intermediate'); // beginner, intermediate, advanced
            $table->string('video_url')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('muscle_group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
