<?php

use App\Enums\JobApplicationStatus;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\Tag;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can list job applications', function () {
    JobApplication::factory(3)->create(['applied_by' => $this->user->id]);

    $response = $this->get(route('job-applications.index'));

    $response->assertStatus(200);
});

it('can list job applications filtered by status', function () {
    $applied = JobApplication::factory()->withStatus(JobApplicationStatus::Applied)->create(['applied_by' => $this->user->id]);
    $offer = JobApplication::factory()->withStatus(JobApplicationStatus::Offer)->create(['applied_by' => $this->user->id]);

    $response = $this->get(route('job-applications.index', ['status' => 'offer']));

    $response->assertStatus(200);
    $response->assertSee($offer->job_title);
    $response->assertDontSee($applied->job_title);
});

it('can list job applications filtered by priority', function () {
    $high = JobApplication::factory()->create(['applied_by' => $this->user->id, 'priority' => 'high']);
    $low = JobApplication::factory()->create(['applied_by' => $this->user->id, 'priority' => 'low']);

    $response = $this->get(route('job-applications.index', ['priority' => 'high']));

    $response->assertStatus(200);
    $response->assertSee($high->job_title);
    $response->assertDontSee($low->job_title);
});

it('can search job applications', function () {
    $matching = JobApplication::factory()->create(['applied_by' => $this->user->id, 'job_title' => 'Software Engineer']);
    $other = JobApplication::factory()->create(['applied_by' => $this->user->id, 'job_title' => 'Data Analyst']);

    $response = $this->get(route('job-applications.index', ['q' => 'Software']));

    $response->assertStatus(200);
    $response->assertSee('Software Engineer');
    $response->assertDontSee('Data Analyst');
});

it('can search job applications by company name', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id, 'name' => 'Acme Corp']);
    $matching = JobApplication::factory()->create(['applied_by' => $this->user->id, 'company_id' => $company->id]);
    $other = JobApplication::factory()->create(['applied_by' => $this->user->id]);

    $response = $this->get(route('job-applications.index', ['q' => 'Acme']));

    $response->assertStatus(200);
    $response->assertSee($matching->job_title);
});

it('can view the kanban board', function () {
    JobApplication::factory(3)->create(['applied_by' => $this->user->id]);

    $response = $this->get(route('job-applications.kanban'));

    $response->assertStatus(200);
});

it('can show a job application', function () {
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id]);

    $response = $this->get(route('job-applications.show', $application));

    $response->assertStatus(200);
    $response->assertSee($application->job_title);
});

it('cannot show another users job application', function () {
    $other = User::factory()->create();
    $application = JobApplication::factory()->create(['applied_by' => $other->id]);

    $response = $this->get(route('job-applications.show', $application));

    $response->assertStatus(403);
});

it('can create a job application', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);

    $response = $this->post(route('job-applications.store'), [
        'job_title' => 'Software Engineer',
        'company_id' => $company->id,
        'status' => 'applied',
        'priority' => 'high',
        'location_type' => 'remote',
        'location_city' => 'Remote',
        'applied_at' => now()->format('Y-m-d'),
        'notes' => 'Exciting opportunity',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('job_applications', [
        'job_title' => 'Software Engineer',
        'applied_by' => $this->user->id,
    ]);
});

it('validates required fields when creating', function () {
    $response = $this->post(route('job-applications.store'), []);

    $response->assertSessionHasErrors(['job_title', 'status', 'priority']);
});

it('requires company_id or new_company_name when creating', function () {
    $response = $this->post(route('job-applications.store'), [
        'job_title' => 'Engineer',
        'status' => 'applied',
        'priority' => 'normal',
    ]);

    $response->assertSessionHasErrors(['company_id']);
});

it('can create a job application with new company', function () {
    $response = $this->post(route('job-applications.store'), [
        'job_title' => 'Software Engineer',
        'new_company_name' => 'New Company Inc',
        'status' => 'applied',
        'priority' => 'high',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('companies', ['name' => 'New Company Inc', 'user_id' => $this->user->id]);
    $this->assertDatabaseHas('job_applications', ['job_title' => 'Software Engineer']);
});

it('can update a job application', function () {
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id]);

    $response = $this->put(route('job-applications.update', $application), [
        'job_title' => 'Updated Title',
        'status' => 'in_review',
        'priority' => 'normal',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('job_applications', [
        'id' => $application->id,
        'job_title' => 'Updated Title',
        'status' => 'in_review',
    ]);
});

it('cannot update another users job application', function () {
    $other = User::factory()->create();
    $application = JobApplication::factory()->create(['applied_by' => $other->id]);

    $response = $this->put(route('job-applications.update', $application), [
        'job_title' => 'Hacked Title',
        'status' => 'applied',
        'priority' => 'normal',
    ]);

    $response->assertStatus(403);
});

it('can update job application status', function () {
    $application = JobApplication::factory()->create([
        'applied_by' => $this->user->id,
        'status' => 'applied',
    ]);

    $response = $this->patchJson(route('job-applications.updateStatus', $application), [
        'status' => 'in_review',
    ]);

    $response->assertStatus(200);
    $this->assertEquals('in_review', $application->fresh()->status->value);
});

it('validates status when updating', function () {
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id]);

    $response = $this->patchJson(route('job-applications.updateStatus', $application), [
        'status' => 'invalid_status',
    ]);

    $response->assertStatus(422);
});

it('cannot update status of another users application', function () {
    $other = User::factory()->create();
    $application = JobApplication::factory()->create(['applied_by' => $other->id]);

    $response = $this->patchJson(route('job-applications.updateStatus', $application), [
        'status' => 'in_review',
    ]);

    $response->assertStatus(403);
});

it('can toggle a tag on a job application', function () {
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id]);
    $tag = Tag::factory()->create(['user_id' => $this->user->id]);

    $response = $this->post(route('job-applications.toggleTag', $application), [
        'tag_id' => $tag->id,
    ]);

    $response->assertRedirect();
    $this->assertTrue($application->fresh()->tags->contains($tag));
});

it('cannot toggle a tag owned by another user', function () {
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id]);
    $other = User::factory()->create();
    $tag = Tag::factory()->create(['user_id' => $other->id]);

    $response = $this->post(route('job-applications.toggleTag', $application), [
        'tag_id' => $tag->id,
    ]);

    $response->assertStatus(403);
});

it('can add a link', function () {
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id, 'links' => []]);

    $response = $this->post(route('job-applications.addLink', $application), [
        'label' => 'Portfolio',
        'url' => 'https://example.com',
    ]);

    $response->assertRedirect();
    $this->assertArrayHasKey('Portfolio', $application->fresh()->links);
});

it('can delete a link', function () {
    $application = JobApplication::factory()->create([
        'applied_by' => $this->user->id,
        'links' => ['Portfolio' => 'https://example.com'],
    ]);

    $response = $this->delete(route('job-applications.deleteLink', $application), [
        'key' => 'Portfolio',
    ]);

    $response->assertRedirect();
    $this->assertArrayNotHasKey('Portfolio', $application->fresh()->links);
});

it('can export job applications as CSV', function () {
    JobApplication::factory()->create(['applied_by' => $this->user->id]);

    $response = $this->get(route('job-applications.export'));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
});

it('can bulk archive applications', function () {
    $apps = JobApplication::factory(3)->create(['applied_by' => $this->user->id]);

    $response = $this->post(route('job-applications.bulk-action'), [
        'selected' => $apps->pluck('id')->toArray(),
        'bulk_action' => 'archive',
    ]);

    $response->assertRedirect();
    foreach ($apps as $app) {
        $this->assertSoftDeleted($app);
    }
});

it('can bulk change status', function () {
    $apps = JobApplication::factory(3)->create(['applied_by' => $this->user->id, 'status' => 'applied']);

    $response = $this->post(route('job-applications.bulk-action'), [
        'selected' => $apps->pluck('id')->toArray(),
        'bulk_action' => 'change_status',
        'bulk_status' => 'in_review',
    ]);

    $response->assertRedirect();
    foreach ($apps as $app) {
        $this->assertEquals('in_review', $app->fresh()->status->value);
    }
});

it('does not bulk archive applications owned by other users', function () {
    $other = User::factory()->create();
    $app = JobApplication::factory()->create(['applied_by' => $other->id]);

    $response = $this->post(route('job-applications.bulk-action'), [
        'selected' => [$app->id],
        'bulk_action' => 'archive',
    ]);

    $response->assertRedirect();
    $this->assertNotSoftDeleted($app);
});

it('shows the create form with companies and tags', function () {
    Company::factory()->create(['user_id' => $this->user->id, 'name' => 'Test Corp']);
    Tag::factory()->create(['user_id' => $this->user->id, 'name' => 'urgent']);

    $response = $this->get(route('job-applications.create'));

    $response->assertStatus(200);
    $response->assertSee('Test Corp');
    $response->assertSee('urgent');
});

it('shows the edit form', function () {
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id]);

    $response = $this->get(route('job-applications.edit', $application));

    $response->assertStatus(200);
    $response->assertSee($application->job_title);
});

it('cannot edit another users application', function () {
    $other = User::factory()->create();
    $application = JobApplication::factory()->create(['applied_by' => $other->id]);

    $response = $this->get(route('job-applications.edit', $application));

    $response->assertStatus(403);
});
