<?php

use App\Models\Company;
use App\Models\Document;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    Storage::fake('public');
});

it('can upload a document', function () {
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id]);
    $file = UploadedFile::fake()->create('resume.pdf', 1024);

    $response = $this->post(route('documents.store'), [
        'file' => $file,
        'documentable_type' => 'App\Models\JobApplication',
        'documentable_id' => $application->id,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('documents', [
        'name' => 'resume.pdf',
        'user_id' => $this->user->id,
        'documentable_type' => 'App\Models\JobApplication',
        'documentable_id' => $application->id,
    ]);
});

it('validates document upload fields', function () {
    $response = $this->post(route('documents.store'), []);

    $response->assertSessionHasErrors(['file', 'documentable_type', 'documentable_id']);
});

it('cannot upload a document to another users entity', function () {
    $other = User::factory()->create();
    $application = JobApplication::factory()->create(['applied_by' => $other->id]);
    $file = UploadedFile::fake()->create('resume.pdf', 1024);

    $response = $this->post(route('documents.store'), [
        'file' => $file,
        'documentable_type' => 'App\Models\JobApplication',
        'documentable_id' => $application->id,
    ]);

    $response->assertSessionHasErrors(['documentable_id']);
});

it('can download a document', function () {
    $file = UploadedFile::fake()->create('resume.pdf', 1024);
    $path = $file->store('documents/'.$this->user->id, 'public');

    $application = JobApplication::factory()->create(['applied_by' => $this->user->id]);
    $document = Document::factory()->create([
        'user_id' => $this->user->id,
        'documentable_type' => 'App\Models\JobApplication',
        'documentable_id' => $application->id,
        'path' => $path,
        'name' => 'resume.pdf',
    ]);

    $response = $this->get(route('documents.download', $document));

    $response->assertStatus(200);
});

it('cannot download another users document', function () {
    $other = User::factory()->create();
    $document = Document::factory()->create(['user_id' => $other->id]);

    $response = $this->get(route('documents.download', $document));

    $response->assertStatus(403);
});

it('can delete a document', function () {
    $application = JobApplication::factory()->create(['applied_by' => $this->user->id]);
    $document = Document::factory()->create([
        'user_id' => $this->user->id,
        'documentable_type' => 'App\Models\JobApplication',
        'documentable_id' => $application->id,
    ]);

    $response = $this->delete(route('documents.destroy', $document));

    $response->assertRedirect();
    $this->assertDatabaseMissing('documents', ['id' => $document->id]);
});

it('cannot delete another users document', function () {
    $other = User::factory()->create();
    $document = Document::factory()->create(['user_id' => $other->id]);

    $response = $this->delete(route('documents.destroy', $document));

    $response->assertStatus(403);
});

it('validates documentable_type must be a valid class', function () {
    $file = UploadedFile::fake()->create('doc.pdf', 1024);

    $response = $this->post(route('documents.store'), [
        'file' => $file,
        'documentable_type' => 'App\Models\InvalidModel',
        'documentable_id' => 1,
    ]);

    $response->assertSessionHasErrors(['documentable_type']);
});

it('uploads documents to company entities', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);
    $file = UploadedFile::fake()->create('contract.pdf', 1024);

    $response = $this->post(route('documents.store'), [
        'file' => $file,
        'documentable_type' => 'App\Models\Company',
        'documentable_id' => $company->id,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('documents', [
        'name' => 'contract.pdf',
        'documentable_type' => 'App\Models\Company',
        'documentable_id' => $company->id,
    ]);
});
