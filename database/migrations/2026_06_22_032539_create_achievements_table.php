<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('icon'); // emoji or path to svg
            $table->string('badge_color', 7)->default('#F59E0B');
            $table->enum('condition_type', [
                'attendances_count',
                'streak_days',
                'weight_goal_reached',
                'first_routine_completed',
                'first_photo_uploaded',
                'measurements_recorded',
                'months_active',
            ]);
            $table->integer('condition_value')->default(1);
            $table->integer('points')->default(10); // gamification points
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
