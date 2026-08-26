<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardRouterController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\AttendanceController as StudentAttendance;
use App\Http\Controllers\Student\ProgressController as StudentProgress;
use App\Http\Controllers\Student\RoutineController as StudentRoutine;
use App\Http\Controllers\Student\WeightLogController as StudentWeightLog;

use App\Http\Controllers\Trainer\DashboardController as TrainerDashboard;
use App\Http\Controllers\Trainer\StudentController as TrainerStudent;
use App\Http\Controllers\Trainer\RoutineController as TrainerRoutine;
use App\Http\Controllers\Trainer\ObservationController as TrainerObservation;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUser;

use Illuminate\Support\Facades\Route;

// QR public link points here
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Database Migration & Seeding Endpoint for Serverless
Route::get('/run-migrations-legionarios', function () {
    try {
        // 1. Direct PostgreSQL DDL execution
        \Illuminate\Support\Facades\DB::unprepared("
            CREATE TABLE IF NOT EXISTS users (
                id BIGSERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                email_verified_at TIMESTAMP NULL,
                password VARCHAR(255) NOT NULL,
                phone VARCHAR(255) NULL,
                emergency_contact_name VARCHAR(255) NULL,
                emergency_contact_phone VARCHAR(255) NULL,
                birth_date DATE NULL,
                blood_type VARCHAR(10) NULL,
                medical_notes TEXT NULL,
                is_active BOOLEAN DEFAULT true,
                avatar_url VARCHAR(255) NULL,
                remember_token VARCHAR(100) NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );

            CREATE TABLE IF NOT EXISTS password_reset_tokens (
                email VARCHAR(255) PRIMARY KEY,
                token VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NULL
            );

            CREATE TABLE IF NOT EXISTS sessions (
                id VARCHAR(255) PRIMARY KEY,
                user_id BIGINT NULL,
                ip_address VARCHAR(45) NULL,
                user_agent TEXT NULL,
                payload TEXT NOT NULL,
                last_activity INT NOT NULL
            );
            CREATE INDEX IF NOT EXISTS sessions_user_id_index ON sessions (user_id);
            CREATE INDEX IF NOT EXISTS sessions_last_activity_index ON sessions (last_activity);

            CREATE TABLE IF NOT EXISTS cache (
                key VARCHAR(255) PRIMARY KEY,
                value TEXT NOT NULL,
                expiration INT NOT NULL
            );

            CREATE TABLE IF NOT EXISTS cache_locks (
                key VARCHAR(255) PRIMARY KEY,
                owner VARCHAR(255) NOT NULL,
                expiration INT NOT NULL
            );

            CREATE TABLE IF NOT EXISTS roles (
                id BIGSERIAL PRIMARY KEY,
                name VARCHAR(255) UNIQUE NOT NULL,
                label VARCHAR(255) NOT NULL,
                description TEXT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );

            CREATE TABLE IF NOT EXISTS user_roles (
                id BIGSERIAL PRIMARY KEY,
                user_id BIGINT NOT NULL,
                role_id BIGINT NOT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );
            CREATE UNIQUE INDEX IF NOT EXISTS user_roles_unique ON user_roles (user_id, role_id);

            CREATE TABLE IF NOT EXISTS membership_plans (
                id BIGSERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                duration_days INT NOT NULL,
                disciplines TEXT NULL,
                is_active BOOLEAN DEFAULT true,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );

            CREATE TABLE IF NOT EXISTS memberships (
                id BIGSERIAL PRIMARY KEY,
                user_id BIGINT NOT NULL,
                membership_plan_id BIGINT NOT NULL,
                start_date DATE NOT NULL,
                end_date DATE NOT NULL,
                status VARCHAR(50) DEFAULT 'active',
                payment_reference VARCHAR(255) NULL,
                payment_method VARCHAR(50) NULL,
                notes TEXT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );

            CREATE TABLE IF NOT EXISTS attendances (
                id BIGSERIAL PRIMARY KEY,
                user_id BIGINT NOT NULL,
                scanned_at TIMESTAMP NOT NULL,
                method VARCHAR(50) DEFAULT 'qr',
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );

            CREATE TABLE IF NOT EXISTS body_stats (
                id BIGSERIAL PRIMARY KEY,
                user_id BIGINT NOT NULL,
                weight_kg DECIMAL(5,2) NOT NULL,
                height_cm DECIMAL(5,2) NULL,
                body_fat_percentage DECIMAL(4,2) NULL,
                muscle_mass_percentage DECIMAL(4,2) NULL,
                chest_cm DECIMAL(5,2) NULL,
                waist_cm DECIMAL(5,2) NULL,
                arms_cm DECIMAL(5,2) NULL,
                legs_cm DECIMAL(5,2) NULL,
                recorded_at DATE NOT NULL,
                notes TEXT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );

            CREATE TABLE IF NOT EXISTS progress_photos (
                id BIGSERIAL PRIMARY KEY,
                user_id BIGINT NOT NULL,
                photo_url VARCHAR(255) NOT NULL,
                photo_type VARCHAR(50) DEFAULT 'front',
                taken_at DATE NOT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );

            CREATE TABLE IF NOT EXISTS exercises (
                id BIGSERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                muscle_group VARCHAR(100) NOT NULL,
                category VARCHAR(100) DEFAULT 'general',
                description TEXT NULL,
                video_url VARCHAR(255) NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );

            CREATE TABLE IF NOT EXISTS routines (
                id BIGSERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT NULL,
                goal VARCHAR(100) NULL,
                level VARCHAR(50) DEFAULT 'intermediate',
                created_by BIGINT NULL,
                is_template BOOLEAN DEFAULT false,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );

            CREATE TABLE IF NOT EXISTS routine_days (
                id BIGSERIAL PRIMARY KEY,
                routine_id BIGINT NOT NULL,
                day_number SMALLINT NOT NULL,
                name VARCHAR(255) NOT NULL,
                focus_area VARCHAR(100) NULL,
                notes TEXT NULL,
                is_rest_day BOOLEAN DEFAULT false,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );

            CREATE TABLE IF NOT EXISTS routine_day_exercises (
                id BIGSERIAL PRIMARY KEY,
                routine_day_id BIGINT NOT NULL,
                exercise_id BIGINT NOT NULL,
                sets SMALLINT DEFAULT 3,
                reps VARCHAR(50) DEFAULT '10',
                weight_kg DECIMAL(6,2) NULL,
                rest_seconds INT DEFAULT 60,
                notes TEXT NULL,
                \"order\" SMALLINT DEFAULT 0,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );

            CREATE TABLE IF NOT EXISTS observations (
                id BIGSERIAL PRIMARY KEY,
                student_id BIGINT NOT NULL,
                trainer_id BIGINT NOT NULL,
                content TEXT NOT NULL,
                category VARCHAR(50) DEFAULT 'general',
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );

            CREATE TABLE IF NOT EXISTS user_routines (
                id BIGSERIAL PRIMARY KEY,
                user_id BIGINT NOT NULL,
                routine_id BIGINT NOT NULL,
                assigned_at DATE NOT NULL,
                ends_at DATE NULL,
                is_active BOOLEAN DEFAULT true,
                notes TEXT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );

            CREATE TABLE IF NOT EXISTS achievements (
                id BIGSERIAL PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                badge_icon VARCHAR(255) NOT NULL,
                category VARCHAR(100) DEFAULT 'attendance',
                points INT DEFAULT 10,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );

            CREATE TABLE IF NOT EXISTS user_achievements (
                id BIGSERIAL PRIMARY KEY,
                user_id BIGINT NOT NULL,
                achievement_id BIGINT NOT NULL,
                awarded_at TIMESTAMP NOT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );

            CREATE TABLE IF NOT EXISTS weight_logs (
                id BIGSERIAL PRIMARY KEY,
                user_id BIGINT NOT NULL,
                exercise_id BIGINT NOT NULL,
                weight_kg DECIMAL(8,2) NOT NULL,
                reps INT DEFAULT 1,
                one_rep_max DECIMAL(8,2) NOT NULL,
                logged_at DATE NOT NULL,
                notes TEXT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );
        ");

        // 2. Run Seeders
        $seeder = new \Database\Seeders\DatabaseSeeder();
        $seeder->run();

        return "<pre>✅ ¡BASE DE DATOS SUPABASE CONFIGURADA Y POBLADA CON ÉXITO!\n\nUsuarios creados:\n- admin@admin.com (password: password)\n- trainer@legionarios.cl (password: password)\n- student@legionarios.cl (password: password)</pre>";
    } catch (\Throwable $e) {
        return "<pre>ERROR: " . htmlspecialchars($e->getMessage()) . "\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
})->withoutMiddleware([
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
]);

// Central Dashboard router after auth
Route::get('/dashboard', DashboardRouterController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Auth Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 1. STUDENT ROUTES
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');
    Route::post('/attendance', [StudentAttendance::class, 'store'])->name('attendance.store');
    
    Route::get('/progress', [StudentProgress::class, 'index'])->name('progress');
    Route::post('/progress/photo', [StudentProgress::class, 'storePhoto'])->name('progress.photo.store');
    
    Route::get('/routine', [StudentRoutine::class, 'index'])->name('routine');
    
    // Weight Logs & Leaderboard
    Route::get('/weights', [StudentWeightLog::class, 'index'])->name('weights.index');
    Route::post('/weights', [StudentWeightLog::class, 'store'])->name('weights.store');
    Route::delete('/weights/{weightLog}', [StudentWeightLog::class, 'destroy'])->name('weights.destroy');
});

// 2. TRAINER ROUTES
Route::middleware(['auth', 'role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/dashboard', [TrainerDashboard::class, 'index'])->name('dashboard');
    
    // Students Management
    Route::get('/students', [TrainerStudent::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [TrainerStudent::class, 'show'])->name('students.show');
    Route::post('/students/{student}/measurements', [TrainerStudent::class, 'storeMeasurements'])->name('students.measurements.store');
    
    // Observations
    Route::post('/students/{student}/observations', [TrainerObservation::class, 'store'])->name('observations.store');
    Route::delete('/observations/{observation}', [TrainerObservation::class, 'destroy'])->name('observations.destroy');
    
    // Routines Management
    Route::get('/routines', [TrainerRoutine::class, 'index'])->name('routines.index');
    Route::get('/routines/create', [TrainerRoutine::class, 'create'])->name('routines.create');
    Route::post('/routines', [TrainerRoutine::class, 'store'])->name('routines.store');
    Route::get('/routines/{routine}', [TrainerRoutine::class, 'show'])->name('routines.show');
    Route::post('/routines/{routine}/duplicate', [TrainerRoutine::class, 'duplicate'])->name('routines.duplicate');
    Route::post('/routines/{routine}/assign', [TrainerRoutine::class, 'assign'])->name('routines.assign');
});

// 3. ADMIN ROUTES
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // Users Management
    Route::get('/users', [AdminUser::class, 'index'])->name('users.index');
    Route::post('/users', [AdminUser::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [AdminUser::class, 'update'])->name('users.update');
});

require __DIR__.'/auth.php';
