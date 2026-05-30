<?php

use App\Models\Company;
use App\Models\Interview;
use App\Models\JobApplication;
use App\Models\Reminder;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can store a reminder', function () {
    $response = $this->postJson('/calendar/reminders', [
        'title' => 'Test Reminder',
        'description' => 'Test description',
        'remind_at' => now()->addDay()->format('Y-m-d H:i:s'),
    ]);

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('reminders', [
        'title' => 'Test Reminder',
        'description' => 'Test description',
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);
});

it('validates required fields when storing', function () {
    $response = $this->postJson('/calendar/reminders', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title', 'remind_at']);
});

it('can update a reminder', function () {
    $reminder = Reminder::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $response = $this->putJson("/calendar/reminders/{$reminder->id}", [
        'title' => 'Updated Title',
        'description' => 'Updated description',
        'remind_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
    ]);

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('reminders', [
        'id' => $reminder->id,
        'title' => 'Updated Title',
        'description' => 'Updated description',
    ]);
});

it('can partially update a reminder', function () {
    $reminder = Reminder::factory()->create([
        'user_id' => $this->user->id,
        'title' => 'Original Title',
    ]);

    $response = $this->putJson("/calendar/reminders/{$reminder->id}", [
        'title' => 'Updated Title',
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('reminders', [
        'id' => $reminder->id,
        'title' => 'Updated Title',
    ]);
});

it('cannot update another users reminder', function () {
    $otherUser = User::factory()->create();
    $reminder = Reminder::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $response = $this->putJson("/calendar/reminders/{$reminder->id}", [
        'title' => 'Hacked Title',
    ]);

    $response->assertStatus(403);
});

it('can delete a reminder', function () {
    $reminder = Reminder::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $response = $this->deleteJson("/calendar/reminders/{$reminder->id}");

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseMissing('reminders', [
        'id' => $reminder->id,
    ]);
});

it('cannot delete another users reminder', function () {
    $otherUser = User::factory()->create();
    $reminder = Reminder::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $response = $this->deleteJson("/calendar/reminders/{$reminder->id}");

    $response->assertStatus(403);
});

it('can complete a reminder', function () {
    $reminder = Reminder::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);

    $response = $this->patchJson("/calendar/reminders/{$reminder->id}/complete");

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('reminders', [
        'id' => $reminder->id,
        'status' => 'sent',
    ]);
    $this->assertNotNull($reminder->fresh()->reminded_at);
});

it('cannot complete another users reminder', function () {
    $otherUser = User::factory()->create();
    $reminder = Reminder::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $response = $this->patchJson("/calendar/reminders/{$reminder->id}/complete");

    $response->assertStatus(403);
});

it('can view calendar events', function () {
    Reminder::factory()->create([
        'user_id' => $this->user->id,
        'remind_at' => now()->addDay(),
        'status' => 'pending',
    ]);

    $response = $this->getJson('/calendar/events?'.http_build_query([
        'month' => now()->month,
        'year' => now()->year,
    ]));

    $response->assertStatus(200);
    $events = $response->json();
    expect($events)->toBeArray();
});

it('can store a reminder linked to an interview', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id, 'name' => 'Acme']);
    $application = JobApplication::factory()->create([
        'applied_by' => $this->user->id,
        'job_title' => 'Engineer',
        'location_type' => 'remote',
        'location_city' => 'Remote',
        'links' => '[]',
        'notes' => '',
        'company_id' => $company->id,
    ]);
    $interview = Interview::factory()->create([
        'user_id' => $this->user->id,
        'job_application_id' => $application->id,
    ]);

    $response = $this->postJson('/calendar/reminders', [
        'title' => 'Interview Reminder',
        'remind_at' => now()->addDay()->format('Y-m-d H:i:s'),
        'remindable_type' => 'App\Models\Interview',
        'remindable_id' => $interview->id,
    ]);

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('reminders', [
        'title' => 'Interview Reminder',
        'remindable_type' => 'App\Models\Interview',
        'remindable_id' => $interview->id,
    ]);
});

it('can store a reminder linked to a job application', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id, 'name' => 'Acme']);
    $application = JobApplication::factory()->create([
        'applied_by' => $this->user->id,
        'job_title' => 'Engineer',
        'location_type' => 'remote',
        'location_city' => 'Remote',
        'links' => '[]',
        'notes' => '',
        'company_id' => $company->id,
    ]);

    $response = $this->postJson('/calendar/reminders', [
        'title' => 'Application Reminder',
        'remind_at' => now()->addDay()->format('Y-m-d H:i:s'),
        'remindable_type' => 'App\Models\JobApplication',
        'remindable_id' => $application->id,
    ]);

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('reminders', [
        'title' => 'Application Reminder',
        'remindable_type' => 'App\Models\JobApplication',
        'remindable_id' => $application->id,
    ]);
});

it('validates remindable_type must be a valid class', function () {
    $response = $this->postJson('/calendar/reminders', [
        'title' => 'Bad Reminder',
        'remind_at' => now()->addDay()->format('Y-m-d H:i:s'),
        'remindable_type' => 'App\Models\InvalidModel',
        'remindable_id' => 1,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['remindable_type']);
});

it('requires remindable_id when remindable_type is provided', function () {
    $response = $this->postJson('/calendar/reminders', [
        'title' => 'Incomplete Reminder',
        'remind_at' => now()->addDay()->format('Y-m-d H:i:s'),
        'remindable_type' => 'App\Models\Interview',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['remindable_id']);
});

it('shows linked reminders on interview show page', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id, 'name' => 'Acme']);
    $application = JobApplication::factory()->create([
        'applied_by' => $this->user->id,
        'job_title' => 'Engineer',
        'location_type' => 'remote',
        'location_city' => 'Remote',
        'links' => '[]',
        'notes' => '',
        'company_id' => $company->id,
    ]);
    $interview = Interview::factory()->create([
        'user_id' => $this->user->id,
        'job_application_id' => $application->id,
    ]);

    Reminder::factory()->create([
        'user_id' => $this->user->id,
        'title' => 'Interview Prep',
        'remindable_type' => 'App\Models\Interview',
        'remindable_id' => $interview->id,
        'remind_at' => now()->addDay(),
    ]);

    $response = $this->get(route('interviews.show', $interview));

    $response->assertStatus(200);
    $response->assertSee('Interview Prep');
    $response->assertSee('Reminders');
});
