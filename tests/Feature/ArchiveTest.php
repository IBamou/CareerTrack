<?php

use App\Models\Company;
use App\Models\Contact;
use App\Models\Interview;
use App\Models\JobApplication;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

// Job Application Archive

it('can archive a job application', function () {
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id]);

    $response = $this->delete(route('job-applications.archive', $application));

    $response->assertRedirect();
    $this->assertSoftDeleted($application);
});

it('can restore an archived job application', function () {
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id]);
    $application->delete();

    $response = $this->post(route('job-applications.restore', $application));

    $response->assertRedirect();
    $this->assertNotSoftDeleted($application);
});

it('can force delete an archived job application', function () {
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id]);
    $application->delete();

    $response = $this->delete(route('job-applications.forceDelete', $application));

    $response->assertRedirect();
    $this->assertDatabaseMissing('job_applications', ['id' => $application->id]);
});

it('cannot archive another users job application', function () {
    $other = User::factory()->create();
    $application = JobApplication::factory()->create(['applied_by' => $other->id]);

    $response = $this->delete(route('job-applications.archive', $application));

    $response->assertStatus(403);
});

it('cannot restore another users job application', function () {
    $other = User::factory()->create();
    $application = JobApplication::factory()->create(['applied_by' => $other->id]);
    $application->delete();

    $response = $this->post(route('job-applications.restore', $application));

    $response->assertStatus(403);
});

it('cannot force delete another users job application', function () {
    $other = User::factory()->create();
    $application = JobApplication::factory()->create(['applied_by' => $other->id]);
    $application->delete();

    $response = $this->delete(route('job-applications.forceDelete', $application));

    $response->assertStatus(403);
});

it('shows archived job applications', function () {
    JobApplication::factory()->create(['applied_by' => $this->user->id])->delete();

    $response = $this->get(route('job-applications.archives'));

    $response->assertStatus(200);
});

// Company Archive

it('can archive a company', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);

    $response = $this->delete(route('companies.archive', $company));

    $response->assertRedirect();
    $this->assertSoftDeleted($company);
});

it('can restore an archived company', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);
    $company->delete();

    $response = $this->post(route('companies.restore', $company));

    $response->assertRedirect();
    $this->assertNotSoftDeleted($company);
});

it('can force delete an archived company', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);
    $company->delete();

    $response = $this->delete(route('companies.forceDelete', $company));

    $response->assertRedirect();
    $this->assertDatabaseMissing('companies', ['id' => $company->id]);
});

it('cannot archive another users company', function () {
    $other = User::factory()->create();
    $company = Company::factory()->create(['user_id' => $other->id]);

    $response = $this->delete(route('companies.archive', $company));

    $response->assertStatus(403);
});

// Interview Archive

it('can archive an interview', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id, 'company_id' => $company->id]);
    $interview = Interview::factory()->create(['user_id' => $this->user->id, 'job_application_id' => $application->id]);

    $response = $this->delete(route('interviews.archive', $interview));

    $response->assertRedirect();
    $this->assertSoftDeleted($interview);
});

it('can restore an archived interview', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id, 'company_id' => $company->id]);
    $interview = Interview::factory()->create(['user_id' => $this->user->id, 'job_application_id' => $application->id]);
    $interview->delete();

    $response = $this->post(route('interviews.restore', $interview));

    $response->assertRedirect();
    $this->assertNotSoftDeleted($interview);
});

it('can force delete an archived interview', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id, 'company_id' => $company->id]);
    $interview = Interview::factory()->create(['user_id' => $this->user->id, 'job_application_id' => $application->id]);
    $interview->delete();

    $response = $this->delete(route('interviews.forceDelete', $interview));

    $response->assertRedirect();
    $this->assertDatabaseMissing('interviews', ['id' => $interview->id]);
});

it('cannot archive another users interview', function () {
    $other = User::factory()->create();
    $company = Company::factory()->create(['user_id' => $other->id]);
    $application = JobApplication::factory()->create(['applied_by' => $other->id, 'company_id' => $company->id]);
    $interview = Interview::factory()->create(['user_id' => $other->id, 'job_application_id' => $application->id]);

    $response = $this->delete(route('interviews.archive', $interview));

    $response->assertStatus(403);
});

// Contact Archive

it('can archive a contact', function () {
    $contact = Contact::factory()->create(['user_id' => $this->user->id]);

    $response = $this->delete(route('contacts.archive', $contact));

    $response->assertRedirect();
    $this->assertSoftDeleted($contact);
});

it('can restore an archived contact', function () {
    $contact = Contact::factory()->create(['user_id' => $this->user->id]);
    $contact->delete();

    $response = $this->post(route('contacts.restore', $contact));

    $response->assertRedirect();
    $this->assertNotSoftDeleted($contact);
});

it('can force delete an archived contact', function () {
    $contact = Contact::factory()->create(['user_id' => $this->user->id]);
    $contact->delete();

    $response = $this->delete(route('contacts.forceDelete', $contact));

    $response->assertRedirect();
    $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
});

it('cannot archive another users contact', function () {
    $other = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $other->id]);

    $response = $this->delete(route('contacts.archive', $contact));

    $response->assertStatus(403);
});

// Archives Index

it('shows the archives index page', function () {
    $response = $this->get(route('archives.index'));

    $response->assertStatus(200);
});
