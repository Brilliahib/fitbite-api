<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CaloryController;
use App\Http\Controllers\CommunityChatController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\PersonalInformationController;
use App\Http\Controllers\WeeklyProgressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Authentication routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    // Auth
    Route::prefix('auth')->group(function () {
        Route::get('/get-auth', [AuthController::class, 'getAuth']);
        Route::put('/change-password', [AuthController::class, 'changePassword']);
        Route::put('/update-account', [AuthController::class, 'updateAccount']);
    });

    // Personal Information
    Route::prefix('personal-information')->group(function () {
        Route::get('/', [PersonalInformationController::class, 'index']);
        Route::post('/', [PersonalInformationController::class, 'store']);
        Route::put('/', [PersonalInformationController::class, 'update']);
        Route::delete('/', [PersonalInformationController::class, 'destroy']);
        Route::get('/check', [PersonalInformationController::class, 'check']);
    });

    // Calory
    Route::prefix('calories')->group(function () {
        Route::get('/', [CaloryController::class, 'index']);
        Route::post('/', [CaloryController::class, 'store']);
        Route::put('/{id}', [CaloryController::class, 'update']);
        Route::delete('/{id}', [CaloryController::class, 'destroy']);
        Route::get('/today', [CaloryController::class, 'getCaloriesToday']);
        Route::get('/week', [CaloryController::class, 'getCaloriesWeek']);
        Route::get('/summary', [CaloryController::class, 'getSummaryCalories']);
        Route::get('/daily-week', [CaloryController::class, 'getDailyCaloriesWeek']);
    });

    // Food
    Route::prefix('food')->group(function () {
        Route::get('/', [FoodController::class, 'index']);
        Route::get('/{id}', [FoodController::class, 'detail']);
    });

    // Blog
    Route::prefix('blog')->group(function () {
        Route::get('/', [BlogController::class, 'index']);
        Route::get('/{slug}', [BlogController::class, 'detail']);
    });

    // Meal Plan
    Route::prefix('meal-plans')->group(function () {
        Route::get('/', [MealPlanController::class, 'index']);
        Route::post('/', [MealPlanController::class, 'store']);
        Route::put('/{id}', [MealPlanController::class, 'update']);
        Route::delete('/{id}', [MealPlanController::class, 'destroy']);
    });

    // Community Chat
    Route::prefix('community-chats')->group(function () {
        Route::get('/', [CommunityChatController::class, 'index']);
        Route::post('/', [CommunityChatController::class, 'store']);
        Route::put('/{id}', [CommunityChatController::class, 'update']);
        Route::delete('/{id}', [CommunityChatController::class, 'destroy']);
    });

    // Weekly Progress
    Route::prefix('weekly-progress')->group(function () {
        Route::get('/', [WeeklyProgressController::class, 'index']);
        Route::post('/', [WeeklyProgressController::class, 'store']);
        Route::put('/{id}', [WeeklyProgressController::class, 'update']);
        Route::delete('/{id}', [WeeklyProgressController::class, 'destroy']);
    });
});
