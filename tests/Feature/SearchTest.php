<?php

use App\Models\Company;
use App\Models\Contact;
use App\Models\JobApplication;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can view search page with empty query', function () {
    $response = $this->get(route('search'));

    $response->assertStatus(200);
});

it('can search by query string', function () {
    JobApplication::factory()->create([
        'applied_by' => $this->user->id,
        'job_title' => 'Software Engineer',
        'location_city' => 'San Francisco',
        'notes' => 'Great opportunity',
    ]);

    $response = $this->get(route('search', ['q' => 'Software']));

    $response->assertStatus(200);
    $response->assertSee('Software Engineer');
});

it('filters search by type', function () {
    JobApplication::factory()->create([
        'applied_by' => $this->user->id,
        'job_title' => 'Engineer Role',
    ]);
    $company = Company::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Engineer Company',
    ]);

    $response = $this->get(route('search', ['q' => 'Engineer', 'type' => 'applications']));

    $response->assertStatus(200);
    $response->assertSee('Engineer Role');
});

it('searches companies', function () {
    Company::factory()->create(['user_id' => $this->user->id, 'name' => 'Tech Corp', 'industry' => 'Technology']);

    $response = $this->get(route('search', ['q' => 'Tech']));

    $response->assertStatus(200);
});

it('searches contacts', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);
    Contact::factory()->create([
        'user_id' => $this->user->id,
        'company_id' => $company->id,
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
    ]);

    $response = $this->get(route('search', ['q' => 'Jane']));

    $response->assertStatus(200);
});

it('shows total results count', function () {
    JobApplication::factory()->create(['applied_by' => $this->user->id, 'job_title' => 'Dev Role']);
    JobApplication::factory()->create(['applied_by' => $this->user->id, 'job_title' => 'Dev Ops']);

    $response = $this->get(route('search', ['q' => 'Dev']));

    $response->assertStatus(200);
});

it('does not show results from other users', function () {
    $other = User::factory()->create();
    JobApplication::factory()->create(['applied_by' => $other->id, 'job_title' => 'Secret Role']);

    $response = $this->get(route('search', ['q' => 'Secret']));

    $response->assertStatus(200);
    $response->assertDontSee('Secret Role');
});

it('validates search type parameter', function () {
    $response = $this->get(route('search', ['q' => 'test', 'type' => 'invalid']));

    $response->assertStatus(200);
});
