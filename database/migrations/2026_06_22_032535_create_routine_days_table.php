<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routine_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('routine_id')->index();
            $table->tinyInteger('day_number'); // 1=Monday, 2=Tuesday ... 7=Sunday
            $table->string('name'); // e.g., "Día A - Pecho y Tríceps"
            $table->string('focus_area')->nullable(); // chest, back, legs, etc.
            $table->text('notes')->nullable();
            $table->boolean('is_rest_day')->default(false);
            $table->timestamps();

            $table->index(['routine_id', 'day_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routine_days');
    }
};
