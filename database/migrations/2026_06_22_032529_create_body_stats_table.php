<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('body_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('weight', 5, 2)->nullable();       // kg
            $table->decimal('height', 5, 2)->nullable();       // cm
            $table->decimal('bmi', 5, 2)->nullable();          // calculated
            $table->decimal('body_fat', 5, 2)->nullable();     // percentage
            $table->decimal('muscle_mass', 5, 2)->nullable();  // kg
            $table->decimal('waist', 5, 2)->nullable();        // cm
            $table->decimal('hip', 5, 2)->nullable();          // cm
            $table->decimal('arm', 5, 2)->nullable();          // cm
            $table->decimal('leg', 5, 2)->nullable();          // cm
            $table->decimal('chest', 5, 2)->nullable();        // cm
            $table->date('measured_at');
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['user_id', 'measured_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('body_stats');
    }
};
