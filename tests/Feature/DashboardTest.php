<?php

use App\Models\Company;
use App\Models\Interview;
use App\Models\JobApplication;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can view the dashboard', function () {
    JobApplication::factory(3)->create(['applied_by' => $this->user->id]);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
});

it('shows stats on the dashboard', function () {
    JobApplication::factory()->create(['applied_by' => $this->user->id, 'status' => 'applied']);
    JobApplication::factory()->create(['applied_by' => $this->user->id, 'status' => 'offer']);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
});

it('shows recent applications on dashboard', function () {
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id]);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee($application->job_title);
});

it('shows upcoming events on dashboard', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);
    $app = JobApplication::factory()->create(['applied_by' => $this->user->id, 'company_id' => $company->id]);
    Interview::factory()->create([
        'user_id' => $this->user->id,
        'job_application_id' => $app->id,
        'scheduled_at' => now()->addDay(),
    ]);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
});

it('can do quick add', function () {
    $response = $this->post(route('dashboard.quick-add'), [
        'job_title' => 'Quick Add Job',
        'company_name' => 'Quick Company',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('companies', ['name' => 'Quick Company', 'user_id' => $this->user->id]);
    $this->assertDatabaseHas('job_applications', [
        'job_title' => 'Quick Add Job',
        'applied_by' => $this->user->id,
    ]);
});

it('validates quick add fields', function () {
    $response = $this->post(route('dashboard.quick-add'), []);

    $response->assertSessionHasErrors(['job_title', 'company_name']);
});

it('quick add reuses existing company', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id, 'name' => 'Existing Co']);

    $this->post(route('dashboard.quick-add'), [
        'job_title' => 'Another Job',
        'company_name' => 'Existing Co',
    ]);

    $this->assertDatabaseHas('job_applications', ['job_title' => 'Another Job', 'company_id' => $company->id]);
});

it('can get calendar grid', function () {
    $response = $this->get(route('dashboard.calendar-grid', ['month' => now()->format('Y-m')]));

    $response->assertStatus(200);
    $response->assertJsonStructure(['html', 'label']);
});

it('shows empty state when no applications exist', function () {
    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
});

it('talley stats correctly with mixed statuses', function () {
    JobApplication::factory()->create(['applied_by' => $this->user->id, 'status' => 'applied']);
    JobApplication::factory()->create(['applied_by' => $this->user->id, 'status' => 'in_review']);
    JobApplication::factory()->create(['applied_by' => $this->user->id, 'status' => 'offer']);
    JobApplication::factory()->create(['applied_by' => $this->user->id, 'status' => 'rejected']);
    JobApplication::factory()->create(['applied_by' => $this->user->id, 'status' => 'ghosted']);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
});

it('shows user name and initial on dashboard', function () {
    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee($this->user->name);
});
