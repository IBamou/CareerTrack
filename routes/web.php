<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/search', [SearchController::class, 'index'])
    ->middleware(['auth'])
    ->name('search');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('/job-applications')->controller(JobApplicationController::class)->group(function () {
        Route::get('/', 'index')->name('job-applications.index');
        Route::get('/archives', 'archives')->name('job-applications.archives');
        Route::get('/create', 'create')->name('job-applications.create');
        Route::post('/store', 'store')->name('job-applications.store');
        Route::get('/{jobApplication}', 'show')->name('job-applications.show');
        Route::get('/{jobApplication}/edit', 'edit')->name('job-applications.edit');
        Route::put('/{jobApplication}/update', 'update')->name('job-applications.update');
        Route::delete('/{jobApplication}/archive', 'archive')->name('job-applications.archive');
        Route::post('/{jobApplication}/restore', 'restore')->name('job-applications.restore')->withTrashed(true);
        Route::delete('/{jobApplication}/forceDelete', 'forceDelete')->name('job-applications.forceDelete')->withTrashed(true);
        Route::patch('/{jobApplication}/updateStatus', 'updateStatus')->name('job-applications.updateStatus');
    });

    Route::prefix('/companies')->controller(CompanyController::class)->group(function () {
        Route::get('/', 'index')->name('companies.index');
        Route::get('/archives', 'archives')->name('companies.archives');
        Route::get('/create', 'create')->name('companies.create');
        Route::post('/store', 'store')->name('companies.store');
        Route::get('/{company}', 'show')->name('companies.show');
        Route::get('/{company}/edit', 'edit')->name('companies.edit');
        Route::put('/{company}/update', 'update')->name('companies.update');
        Route::delete('/{company}/archive', 'archive')->name('companies.archive');
        Route::post('/{company}/restore', 'restore')->name('companies.restore')->withTrashed(true);
        Route::delete('/{company}/forceDelete', 'forceDelete')->name('companies.forceDelete')->withTrashed(true);
    });
});

require __DIR__.'/auth.php';
