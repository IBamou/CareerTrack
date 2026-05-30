<?php

use App\Models\Company;
use App\Models\Interview;
use App\Models\JobApplication;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can list interviews', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id, 'company_id' => $company->id]);
    Interview::factory(3)->create(['user_id' => $this->user->id, 'job_application_id' => $application->id]);

    $response = $this->get(route('interviews.index'));

    $response->assertStatus(200);
});

it('can show an interview', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id, 'company_id' => $company->id]);
    $interview = Interview::factory()->create([
        'user_id' => $this->user->id,
        'job_application_id' => $application->id,
    ]);

    $response = $this->get(route('interviews.show', $interview));

    $response->assertStatus(200);
    $response->assertSee($interview->type);
});

it('cannot show another users interview', function () {
    $other = User::factory()->create();
    $interview = Interview::factory()->create(['user_id' => $other->id]);

    $response = $this->get(route('interviews.show', $interview));

    $response->assertStatus(403);
});

it('can create an interview', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id, 'company_id' => $company->id]);

    $response = $this->post(route('interviews.store'), [
        'job_application_id' => $application->id,
        'type' => 'Phone Screen',
        'scheduled_at' => now()->addDay()->format('Y-m-d H:i:s'),
        'notes' => 'Be prepared',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('interviews', [
        'type' => 'Phone Screen',
        'user_id' => $this->user->id,
    ]);
});

it('validates required fields when creating an interview', function () {
    $response = $this->post(route('interviews.store'), []);

    $response->assertSessionHasErrors(['job_application_id', 'type', 'scheduled_at']);
});

it('cannot create an interview for another users application', function () {
    $other = User::factory()->create();
    $application = JobApplication::factory()->create(['applied_by' => $other->id]);

    $response = $this->post(route('interviews.store'), [
        'job_application_id' => $application->id,
        'type' => 'Phone',
        'scheduled_at' => now()->addDay()->format('Y-m-d H:i:s'),
    ]);

    $response->assertSessionHasErrors(['job_application_id']);
});

it('can update an interview', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id, 'company_id' => $company->id]);
    $interview = Interview::factory()->create([
        'user_id' => $this->user->id,
        'job_application_id' => $application->id,
    ]);

    $response = $this->put(route('interviews.update', $interview), [
        'type' => 'Technical Interview',
        'scheduled_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('interviews', [
        'id' => $interview->id,
        'type' => 'Technical Interview',
    ]);
});

it('cannot update another users interview', function () {
    $other = User::factory()->create();
    $interview = Interview::factory()->create(['user_id' => $other->id]);

    $response = $this->put(route('interviews.update', $interview), [
        'type' => 'Hacked',
        'scheduled_at' => now()->format('Y-m-d H:i:s'),
    ]);

    $response->assertStatus(403);
});

it('shows the create interview form', function () {
    $response = $this->get(route('interviews.create'));

    $response->assertStatus(200);
});

it('shows the edit interview form', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id, 'company_id' => $company->id]);
    $interview = Interview::factory()->create([
        'user_id' => $this->user->id,
        'job_application_id' => $application->id,
    ]);

    $response = $this->get(route('interviews.edit', $interview));

    $response->assertStatus(200);
    $response->assertSee($interview->type);
});
