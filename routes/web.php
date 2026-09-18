<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\JobManagementController;
use App\Http\Controllers\Admin\ScrapingLogController;
use App\Http\Controllers\Admin\ScrapingSourceController;
use App\Http\Controllers\Admin\SkillManagementController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ExperienceController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\RecommendationController;
use App\Http\Controllers\User\SavedJobController;
use App\Http\Controllers\User\SkillController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Job Seeker Protected Routes (role: job_seeker)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:job_seeker'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/recommendations', [RecommendationController::class, 'index'])->name('user.recommendations');
    Route::get('/saved-jobs', [SavedJobController::class, 'index'])->name('user.saved-jobs');
    Route::post('/jobs/{id}/save', [JobController::class, 'toggleSave'])->name('jobs.save');

    // Profile Management
    Route::prefix('profile')->name('user.profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/', [ProfileController::class, 'update'])->name('update');

        // Skills
        Route::get('/skills', [SkillController::class, 'index'])->name('skills');
        Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
        Route::delete('/skills/{id}', [SkillController::class, 'destroy'])->name('skills.destroy');

        // Experiences
        Route::get('/experiences', [ExperienceController::class, 'index'])->name('experiences');
        Route::post('/experiences', [ExperienceController::class, 'store'])->name('experiences.store');
        Route::delete('/experiences/{id}', [ExperienceController::class, 'destroy'])->name('experiences.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Protected Routes (role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Scraping Sources
    Route::prefix('scraping-sources')->name('scraping-sources.')->group(function () {
        Route::get('/', [ScrapingSourceController::class, 'index'])->name('index');
        Route::get('/create', [ScrapingSourceController::class, 'create'])->name('create');
        Route::post('/', [ScrapingSourceController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ScrapingSourceController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ScrapingSourceController::class, 'update'])->name('update');
        Route::patch('/{id}/toggle', [ScrapingSourceController::class, 'toggle'])->name('toggle');
        Route::post('/{id}/scrape', [ScrapingSourceController::class, 'triggerScrape'])->name('scrape');
        Route::post('/scrape-all', [ScrapingSourceController::class, 'triggerScrapeAll'])->name('scrape-all');
    });

    // Scraping Logs
    Route::prefix('scraping-logs')->name('scraping-logs.')->group(function () {
        Route::get('/', [ScrapingLogController::class, 'index'])->name('index');
        Route::get('/{id}', [ScrapingLogController::class, 'show'])->name('show');
    });

    // Jobs Management
    Route::prefix('jobs')->name('jobs.')->group(function () {
        Route::get('/', [JobManagementController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [JobManagementController::class, 'edit'])->name('edit');
        Route::put('/{id}', [JobManagementController::class, 'update'])->name('update');
        Route::patch('/{id}/status', [JobManagementController::class, 'toggleStatus'])->name('status');
    });

    // Master Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Master Skills
    Route::prefix('skills')->name('skills.')->group(function () {
        Route::get('/', [SkillManagementController::class, 'index'])->name('index');
        Route::post('/', [SkillManagementController::class, 'store'])->name('store');
        Route::put('/{id}', [SkillManagementController::class, 'update'])->name('update');
        Route::delete('/{id}', [SkillManagementController::class, 'destroy'])->name('destroy');
    });

    // Users Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/{id}', [UserManagementController::class, 'show'])->name('show');
        Route::patch('/{id}/toggle', [UserManagementController::class, 'toggleStatus'])->name('toggle');
    });

    // Statistics
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
});
