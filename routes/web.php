<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkLogController;
use App\Http\Controllers\WorkLogTemplateController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard & protected routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile page
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    // Example page
    Route::get('/reports', function () {
        return view('reports');
    })->name('reports');
    
    Route::middleware(['auth'])->group(function () {
        Route::resource('work-logs', App\Http\Controllers\WorkLogController::class);
    });

    Route::get('/work-log-generator', [WorkLogTemplateController::class, 'index'])->name('worklog.generator');
    Route::post('/work-log-generator', [WorkLogTemplateController::class, 'generate'])->name('worklog.generate');
    Route::get('/work-log-history', [WorkLogTemplateController::class, 'history'])->name('worklog.history');
    Route::post('/work-log-generator-ajax', [WorkLogTemplateController::class, 'generateAjax'])->name('worklog.generate.ajax');
    Route::post('/work-log-fetch-previous', [WorkLogTemplateController::class, 'fetchPreviousWork'])->name('worklog.fetch.previous');
    Route::post('/work-log-save-ajax', [WorkLogTemplateController::class, 'saveAjax'])->name('worklog.save.ajax');
});

require __DIR__.'/auth.php';
