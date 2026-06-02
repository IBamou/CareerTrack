<?php

use App\Http\Controllers\ArchivesController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
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

Route::middleware('auth')->group(function () {

    /* ─── Dashboard ───────────────────────────────────────────────── */

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::post('/dashboard/quick-add', 'quickAdd')->name('dashboard.quick-add')->middleware('throttle:ajax');
        Route::get('/dashboard/calendar-grid', 'calendarGrid')->name('dashboard.calendar-grid')->middleware('throttle:ajax');
    });

    /* ─── Search / Archives / Notifications / Profile ─────────────── */

    Route::get('/search', [SearchController::class, 'index'])->name('search');

    Route::get('/archives', [ArchivesController::class, 'index'])->name('archives.index');

    Route::controller(NotificationController::class)->group(function () {
        Route::get('/notifications', 'index')->name('notifications.index');
        Route::post('/notifications/{id}/mark-read', 'markAsRead')->name('notifications.mark-read')->middleware('throttle:ajax');
        Route::post('/notifications/mark-all-read', 'markAllAsRead')->name('notifications.mark-all-read')->middleware('throttle:ajax');
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update')->middleware('throttle:forms');
        Route::delete('/profile', 'destroy')->name('profile.destroy')->middleware('throttle:forms');
    });

    /* ─── Job Applications ────────────────────────────────────────── */

    Route::prefix('/job-applications')->controller(JobApplicationController::class)->group(function () {
        Route::get('/', 'index')->name('job-applications.index');
        Route::get('/kanban', 'kanban')->name('job-applications.kanban');
        Route::get('/export', 'export')->name('job-applications.export');
        Route::get('/archives', 'archives')->name('job-applications.archives');
        Route::get('/create', 'create')->name('job-applications.create');
        Route::post('/', 'store')->name('job-applications.store')->middleware('throttle:forms');
        Route::get('/{jobApplication}', 'show')->name('job-applications.show')->withTrashed(true);
        Route::get('/{jobApplication}/edit', 'edit')->name('job-applications.edit')->withTrashed(true);
        Route::put('/{jobApplication}', 'update')->name('job-applications.update')->withTrashed(true)->middleware('throttle:forms');
        Route::delete('/{jobApplication}', 'archive')->name('job-applications.archive')->middleware('throttle:forms');
        Route::post('/{jobApplication}/restore', 'restore')->name('job-applications.restore')->withTrashed(true)->middleware('throttle:forms');
        Route::delete('/{jobApplication}/force', 'forceDelete')->name('job-applications.forceDelete')->withTrashed(true)->middleware('throttle:forms');
        Route::patch('/{jobApplication}/status', 'updateStatus')->name('job-applications.updateStatus')->middleware('throttle:ajax');
        Route::post('/{jobApplication}/tags', 'toggleTag')->name('job-applications.toggleTag')->middleware('throttle:ajax');
        Route::post('/{jobApplication}/links', 'addLink')->name('job-applications.addLink')->middleware('throttle:ajax');
        Route::delete('/{jobApplication}/links', 'deleteLink')->name('job-applications.deleteLink')->middleware('throttle:ajax');
        Route::post('/bulk-action', 'bulkAction')->name('job-applications.bulk-action')->middleware('throttle:ajax');
    });

    /* ─── Contacts ────────────────────────────────────────────────── */

    Route::prefix('/contacts')->controller(ContactController::class)->group(function () {
        Route::get('/', 'index')->name('contacts.index');
        Route::get('/archives', 'archives')->name('contacts.archives');
        Route::get('/create', 'create')->name('contacts.create');
        Route::post('/', 'store')->name('contacts.store')->middleware('throttle:forms');
        Route::get('/{contact}', 'show')->name('contacts.show')->withTrashed(true);
        Route::get('/{contact}/edit', 'edit')->name('contacts.edit')->withTrashed(true);
        Route::put('/{contact}', 'update')->name('contacts.update')->withTrashed(true)->middleware('throttle:forms');
        Route::delete('/{contact}', 'archive')->name('contacts.archive')->middleware('throttle:forms');
        Route::post('/{contact}/restore', 'restore')->name('contacts.restore')->withTrashed(true)->middleware('throttle:forms');
        Route::delete('/{contact}/force', 'forceDelete')->name('contacts.forceDelete')->withTrashed(true)->middleware('throttle:forms');
    });

    /* ─── Companies ───────────────────────────────────────────────── */

    Route::prefix('/companies')->controller(CompanyController::class)->group(function () {
        Route::get('/', 'index')->name('companies.index');
        Route::get('/archives', 'archives')->name('companies.archives');
        Route::get('/create', 'create')->name('companies.create');
        Route::post('/', 'store')->name('companies.store')->middleware('throttle:forms');
        Route::get('/{company}', 'show')->name('companies.show')->withTrashed(true);
        Route::get('/{company}/edit', 'edit')->name('companies.edit')->withTrashed(true);
        Route::put('/{company}', 'update')->name('companies.update')->withTrashed(true)->middleware('throttle:forms');
        Route::delete('/{company}', 'archive')->name('companies.archive')->middleware('throttle:forms');
        Route::post('/{company}/tags', 'toggleTag')->name('companies.toggleTag')->middleware('throttle:ajax');
        Route::post('/{company}/restore', 'restore')->name('companies.restore')->withTrashed(true)->middleware('throttle:forms');
        Route::delete('/{company}/force', 'forceDelete')->name('companies.forceDelete')->withTrashed(true)->middleware('throttle:forms');
    });

    /* ─── Interviews ──────────────────────────────────────────────── */

    Route::prefix('/interviews')->controller(InterviewController::class)->group(function () {
        Route::get('/', 'index')->name('interviews.index');
        Route::get('/archives', 'archives')->name('interviews.archives');
        Route::get('/create', 'create')->name('interviews.create');
        Route::post('/', 'store')->name('interviews.store')->middleware('throttle:forms');
        Route::get('/{interview}', 'show')->name('interviews.show')->withTrashed(true);
        Route::get('/{interview}/edit', 'edit')->name('interviews.edit')->withTrashed(true);
        Route::put('/{interview}', 'update')->name('interviews.update')->withTrashed(true)->middleware('throttle:forms');
        Route::delete('/{interview}', 'archive')->name('interviews.archive')->middleware('throttle:forms');
        Route::post('/{interview}/restore', 'restore')->name('interviews.restore')->withTrashed(true)->middleware('throttle:forms');
        Route::delete('/{interview}/force', 'forceDelete')->name('interviews.forceDelete')->withTrashed(true)->middleware('throttle:forms');
    });

    /* ─── Calendar & Reminders ────────────────────────────────────── */

    Route::prefix('/calendar')->controller(CalendarController::class)->group(function () {
        Route::get('/', 'index')->name('calendar.index');
        Route::get('/events', 'events')->name('calendar.events')->middleware('throttle:ajax');
        Route::post('/reminders', 'storeReminder')->name('calendar.reminders.store')->middleware('throttle:ajax');
        Route::put('/reminders/{reminder}', 'updateReminder')->name('calendar.reminders.update')->middleware('throttle:ajax');
        Route::patch('/reminders/{reminder}/complete', 'completeReminder')->name('calendar.reminders.complete')->middleware('throttle:ajax');
        Route::delete('/reminders/{reminder}', 'destroyReminder')->name('calendar.reminders.destroy')->middleware('throttle:ajax');
    });

    /* ─── Documents ───────────────────────────────────────────────── */

    Route::prefix('/documents')->controller(DocumentController::class)->group(function () {
        Route::post('/', 'store')->name('documents.store')->middleware('throttle:forms');
        Route::get('/{document}/download', 'download')->name('documents.download');
        Route::delete('/{document}', 'destroy')->name('documents.destroy')->middleware('throttle:forms');
    });

    /* ─── Tags ────────────────────────────────────────────────────── */

    Route::prefix('/tags')->controller(TagController::class)->group(function () {
        Route::get('/', 'index')->name('tags.index');
        Route::post('/', 'store')->name('tags.store')->middleware('throttle:forms');
        Route::put('/{tag}', 'update')->name('tags.update')->middleware('throttle:forms');
        Route::delete('/{tag}', 'destroy')->name('tags.destroy')->middleware('throttle:forms');
        Route::get('/{tag}', 'show')->name('tags.show');
    });
});

require __DIR__.'/auth.php';
