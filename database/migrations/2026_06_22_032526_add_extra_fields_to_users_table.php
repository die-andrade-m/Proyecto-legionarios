<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('avatar');
            $table->text('objective')->nullable()->after('birth_date');
            $table->foreignId('trainer_id')->nullable()->constrained('users')->nullOnDelete()->after('objective');
            $table->boolean('is_active')->default(true)->after('trainer_id');
            $table->string('emergency_contact_name')->nullable()->after('is_active');
            $table->string('emergency_contact_phone', 20)->nullable()->after('emergency_contact_name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'avatar', 'birth_date', 'objective', 'trainer_id', 'is_active', 'emergency_contact_name', 'emergency_contact_phone']);
        });
    }
};
