<?php

use App\Http\Controllers\ArchivesController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('/dashboard/quick-add', [DashboardController::class, 'quickAdd'])
    ->middleware(['auth'])
    ->name('dashboard.quick-add');

Route::get('/search', [SearchController::class, 'index'])
    ->middleware(['auth'])
    ->name('search');

Route::get('/archives', [ArchivesController::class, 'index'])
    ->middleware(['auth'])
    ->name('archives.index');

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('/job-applications')->controller(JobApplicationController::class)->group(function () {
        Route::get('/', 'index')->name('job-applications.index');
        Route::get('/kanban', 'kanban')->name('job-applications.kanban');
        Route::get('/export', 'export')->name('job-applications.export');
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
        Route::post('/bulk-action', 'bulkAction')->name('job-applications.bulk-action');
    });

    Route::prefix('/contacts')->controller(ContactController::class)->group(function () {
        Route::get('/', 'index')->name('contacts.index');
        Route::get('/archives', 'archives')->name('contacts.archives');
        Route::get('/create', 'create')->name('contacts.create');
        Route::post('/store', 'store')->name('contacts.store');
        Route::get('/{contact}', 'show')->name('contacts.show');
        Route::get('/{contact}/edit', 'edit')->name('contacts.edit');
        Route::put('/{contact}/update', 'update')->name('contacts.update');
        Route::delete('/{contact}/archive', 'archive')->name('contacts.archive');
        Route::post('/{contact}/restore', 'restore')->name('contacts.restore')->withTrashed(true);
        Route::delete('/{contact}/forceDelete', 'forceDelete')->name('contacts.forceDelete')->withTrashed(true);
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

    Route::prefix('/interviews')->controller(InterviewController::class)->group(function () {
        Route::get('/', 'index')->name('interviews.index');
        Route::get('/archives', 'archives')->name('interviews.archives');
        Route::get('/create', 'create')->name('interviews.create');
        Route::post('/store', 'store')->name('interviews.store');
        Route::get('/{interview}', 'show')->name('interviews.show');
        Route::get('/{interview}/edit', 'edit')->name('interviews.edit');
        Route::put('/{interview}/update', 'update')->name('interviews.update');
        Route::delete('/{interview}/archive', 'archive')->name('interviews.archive');
        Route::post('/{interview}/restore', 'restore')->name('interviews.restore')->withTrashed(true);
        Route::delete('/{interview}/forceDelete', 'forceDelete')->name('interviews.forceDelete')->withTrashed(true);
    });

    Route::prefix('/calendar')->controller(CalendarController::class)->group(function () {
        Route::get('/', 'index')->name('calendar.index');
        Route::get('/events', 'events')->name('calendar.events');
        Route::post('/reminders', 'storeReminder')->name('calendar.reminders.store');
        Route::patch('/reminders/{reminder}/complete', 'completeReminder')->name('calendar.reminders.complete');
    });

    Route::prefix('/documents')->controller(DocumentController::class)->group(function () {
        Route::post('/store', 'store')->name('documents.store');
        Route::get('/{document}/download', 'download')->name('documents.download');
        Route::delete('/{document}', 'destroy')->name('documents.destroy');
    });

    Route::prefix('/tags')->controller(TagController::class)->group(function () {
        Route::get('/', 'index')->name('tags.index');
        Route::post('/store', 'store')->name('tags.store');
        Route::put('/{tag}/update', 'update')->name('tags.update');
        Route::delete('/{tag}', 'destroy')->name('tags.destroy');
        Route::get('/{tag}', 'show')->name('tags.show');
    });
});

require __DIR__.'/auth.php';
