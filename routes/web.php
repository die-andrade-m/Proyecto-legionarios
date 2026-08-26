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
        \Illuminate\Support\Facades\Artisan::call('migrate', [
            '--force' => true,
        ]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--force' => true,
        ]);
        $seedOutput = \Illuminate\Support\Facades\Artisan::output();

        return response()->json([
            'status' => 'success',
            'migrate' => $migrateOutput,
            'seed' => $seedOutput,
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
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
