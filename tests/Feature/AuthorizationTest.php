<?php

use App\Models\Company;
use App\Models\Contact;
use App\Models\Document;
use App\Models\Interview;
use App\Models\JobApplication;
use App\Models\Reminder;
use App\Models\Tag;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->other = User::factory()->create();
});

it('unauthenticated users cannot access any route', function () {
    Auth::logout();

    $this->get(route('dashboard'))->assertRedirect(route('login'));
    $this->get(route('job-applications.index'))->assertRedirect(route('login'));
    $this->get(route('companies.index'))->assertRedirect(route('login'));
    $this->get(route('interviews.index'))->assertRedirect(route('login'));
    $this->get(route('contacts.index'))->assertRedirect(route('login'));
    $this->get(route('tags.index'))->assertRedirect(route('login'));
    $this->get(route('calendar.index'))->assertRedirect(route('login'));
    $this->get(route('search'))->assertRedirect(route('login'));
    $this->get(route('archives.index'))->assertRedirect(route('login'));
});

it('email unverified users cannot access the dashboard', function () {
    $unverified = User::factory()->unverified()->create();
    $this->actingAs($unverified);

    $response = $this->get(route('dashboard'));
    expect($response->status())->toBeIn([302, 200]);
});

it('user cannot view another users job application', function () {
    $application = JobApplication::factory()->create(['applied_by' => $this->other->id]);

    $this->get(route('job-applications.show', $application))->assertStatus(403);
});

it('user cannot update another users job application', function () {
    $application = JobApplication::factory()->create(['applied_by' => $this->other->id]);

    $this->put(route('job-applications.update', $application), [
        'job_title' => 'Hacked',
        'status' => 'applied',
        'priority' => 'normal',
    ])->assertStatus(403);
});

it('user cannot archive another users job application', function () {
    $application = JobApplication::factory()->create(['applied_by' => $this->other->id]);

    $this->delete(route('job-applications.archive', $application))->assertStatus(403);
});

it('user cannot view another users company', function () {
    $company = Company::factory()->create(['user_id' => $this->other->id]);

    $this->get(route('companies.show', $company))->assertStatus(403);
});

it('user cannot update another users company', function () {
    $company = Company::factory()->create(['user_id' => $this->other->id]);

    $this->put(route('companies.update', $company), ['name' => 'Hacked'])->assertStatus(403);
});

it('user cannot archive another users company', function () {
    $company = Company::factory()->create(['user_id' => $this->other->id]);

    $this->delete(route('companies.archive', $company))->assertStatus(403);
});

it('user cannot view another users interview', function () {
    $interview = Interview::factory()->create(['user_id' => $this->other->id]);

    $this->get(route('interviews.show', $interview))->assertStatus(403);
});

it('user cannot update another users interview', function () {
    $interview = Interview::factory()->create(['user_id' => $this->other->id]);

    $this->put(route('interviews.update', $interview), [
        'type' => 'Technical',
        'scheduled_at' => now()->format('Y-m-d H:i:s'),
    ])->assertStatus(403);
});

it('user cannot view another users contact', function () {
    $contact = Contact::factory()->create(['user_id' => $this->other->id]);

    $this->get(route('contacts.show', $contact))->assertStatus(403);
});

it('user cannot update another users contact', function () {
    $contact = Contact::factory()->create(['user_id' => $this->other->id]);

    $this->put(route('contacts.update', $contact), ['name' => 'Hacked'])->assertStatus(403);
});

it('user cannot view another users tag', function () {
    $tag = Tag::factory()->create(['user_id' => $this->other->id]);

    $this->get(route('tags.show', $tag))->assertStatus(403);
});

it('user cannot update another users tag', function () {
    $tag = Tag::factory()->create(['user_id' => $this->other->id]);

    $this->put(route('tags.update', $tag), ['name' => 'Hacked'])->assertStatus(403);
});

it('user cannot delete another users tag', function () {
    $tag = Tag::factory()->create(['user_id' => $this->other->id]);

    $this->delete(route('tags.destroy', $tag))->assertStatus(403);
});

it('user cannot download another users document', function () {
    $document = Document::factory()->create(['user_id' => $this->other->id]);

    $this->get(route('documents.download', $document))->assertStatus(403);
});

it('user cannot delete another users document', function () {
    $document = Document::factory()->create(['user_id' => $this->other->id]);

    $this->delete(route('documents.destroy', $document))->assertStatus(403);
});

it('user cannot update another users reminder', function () {
    $reminder = Reminder::factory()->create(['user_id' => $this->other->id]);

    $this->putJson(route('calendar.reminders.update', $reminder), ['title' => 'Hacked'])->assertStatus(403);
});

it('user cannot delete another users reminder', function () {
    $reminder = Reminder::factory()->create(['user_id' => $this->other->id]);

    $this->deleteJson(route('calendar.reminders.destroy', $reminder))->assertStatus(403);
});

it('user cannot complete another users reminder', function () {
    $reminder = Reminder::factory()->create(['user_id' => $this->other->id]);

    $this->patchJson(route('calendar.reminders.complete', $reminder))->assertStatus(403);
});
