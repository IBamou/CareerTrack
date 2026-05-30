<?php

use App\Models\Company;
use App\Models\Contact;
use App\Models\Tag;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can list companies', function () {
    Company::factory(3)->create(['user_id' => $this->user->id]);

    $response = $this->get(route('companies.index'));

    $response->assertStatus(200);
});

it('can search companies', function () {
    Company::factory()->create(['user_id' => $this->user->id, 'name' => 'Acme Corp']);
    Company::factory()->create(['user_id' => $this->user->id, 'name' => 'Beta Inc']);

    $response = $this->get(route('companies.index', ['q' => 'Acme']));

    $response->assertStatus(200);
    $response->assertSee('Acme Corp');
    $response->assertDontSee('Beta Inc');
});

it('can show a company', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);

    $response = $this->get(route('companies.show', $company));

    $response->assertStatus(200);
    $response->assertSee($company->name);
});

it('cannot show another users company', function () {
    $other = User::factory()->create();
    $company = Company::factory()->create(['user_id' => $other->id]);

    $response = $this->get(route('companies.show', $company));

    $response->assertStatus(403);
});

it('can create a company', function () {
    $response = $this->post(route('companies.store'), [
        'name' => 'New Company',
        'website' => 'https://example.com',
        'industry' => 'Technology',
        'location' => 'San Francisco, USA',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('companies', [
        'name' => 'New Company',
        'user_id' => $this->user->id,
    ]);
});

it('validates required fields when creating a company', function () {
    $response = $this->post(route('companies.store'), []);

    $response->assertSessionHasErrors(['name']);
});

it('validates unique company name per user', function () {
    Company::factory()->create(['user_id' => $this->user->id, 'name' => 'Duplicate']);

    $response = $this->post(route('companies.store'), ['name' => 'Duplicate']);

    $response->assertSessionHasErrors(['name']);
});

it('allows the same company name for different users', function () {
    $other = User::factory()->create();
    Company::factory()->create(['user_id' => $other->id, 'name' => 'Shared Name']);

    $response = $this->post(route('companies.store'), ['name' => 'Shared Name']);

    $response->assertSessionDoesntHaveErrors('name');
});

it('can update a company', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);

    $response = $this->put(route('companies.update', $company), [
        'name' => 'Updated Name',
        'notes' => 'Updated notes',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('companies', [
        'id' => $company->id,
        'name' => 'Updated Name',
    ]);
});

it('cannot update another users company', function () {
    $other = User::factory()->create();
    $company = Company::factory()->create(['user_id' => $other->id]);

    $response = $this->put(route('companies.update', $company), [
        'name' => 'Hacked Name',
    ]);

    $response->assertStatus(403);
});

it('can toggle a tag on a company', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);
    $tag = Tag::factory()->create(['user_id' => $this->user->id]);

    $response = $this->post(route('companies.toggleTag', $company), [
        'tag_id' => $tag->id,
    ]);

    $response->assertRedirect();
    $this->assertTrue($company->fresh()->tags->contains($tag));
});

it('cannot toggle a tag owned by another user on a company', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);
    $other = User::factory()->create();
    $tag = Tag::factory()->create(['user_id' => $other->id]);

    $response = $this->post(route('companies.toggleTag', $company), [
        'tag_id' => $tag->id,
    ]);

    $response->assertStatus(403);
});

it('shows paginated contacts on company page', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);
    Contact::factory(3)->create(['user_id' => $this->user->id, 'company_id' => $company->id]);

    $response = $this->get(route('companies.show', $company));

    $response->assertStatus(200);
});

it('cannot view another users company archives', function () {
    $other = User::factory()->create();
    Company::factory()->create(['user_id' => $other->id])->delete();

    $response = $this->get(route('companies.archives'));

    $response->assertStatus(200);
});

it('shows the create company form', function () {
    $response = $this->get(route('companies.create'));

    $response->assertStatus(200);
});

it('shows the edit company form', function () {
    $company = Company::factory()->create(['user_id' => $this->user->id]);

    $response = $this->get(route('companies.edit', $company));

    $response->assertStatus(200);
    $response->assertSee($company->name);
});
