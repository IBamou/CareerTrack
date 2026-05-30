<?php

use App\Models\Company;
use App\Models\Contact;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can list contacts', function () {
    Contact::factory(3)->create(['user_id' => $this->user->id]);

    $response = $this->get(route('contacts.index'));

    $response->assertStatus(200);
});

it('can show a contact', function () {
    $contact = Contact::factory()->create(['user_id' => $this->user->id]);

    $response = $this->get(route('contacts.show', $contact));

    $response->assertStatus(200);
    $response->assertSee($contact->name);
});

it('cannot show another users contact', function () {
    $other = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $other->id]);

    $response = $this->get(route('contacts.show', $contact));

    $response->assertStatus(403);
});

it('can create a contact', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);

    $response = $this->post(route('contacts.store'), [
        'company_id' => $company->id,
        'name' => 'John Doe',
        'role' => 'Hiring Manager',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('contacts', [
        'name' => 'John Doe',
        'user_id' => $this->user->id,
    ]);
});

it('validates required fields when creating a contact', function () {
    $response = $this->post(route('contacts.store'), []);

    $response->assertSessionHasErrors(['company_id', 'name']);
});

it('cannot create a contact for another users company', function () {
    $other = User::factory()->create();
    $company = Company::factory()->create(['user_id' => $other->id]);

    $response = $this->post(route('contacts.store'), [
        'company_id' => $company->id,
        'name' => 'John Doe',
    ]);

    $response->assertSessionHasErrors(['company_id']);
});

it('can update a contact', function () {
    $contact = Contact::factory()->create(['user_id' => $this->user->id]);

    $response = $this->put(route('contacts.update', $contact), [
        'name' => 'Jane Doe',
        'role' => 'Recruiter',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('contacts', [
        'id' => $contact->id,
        'name' => 'Jane Doe',
    ]);
});

it('cannot update another users contact', function () {
    $other = User::factory()->create();
    $contact = Contact::factory()->create(['user_id' => $other->id]);

    $response = $this->put(route('contacts.update', $contact), [
        'name' => 'Hacked Name',
    ]);

    $response->assertStatus(403);
});

it('shows the create contact form', function () {
    $response = $this->get(route('contacts.create'));

    $response->assertStatus(200);
});

it('shows the edit contact form', function () {
    $contact = Contact::factory()->create(['user_id' => $this->user->id]);

    $response = $this->get(route('contacts.edit', $contact));

    $response->assertStatus(200);
    $response->assertSee($contact->name);
});
