<?php

use App\Models\Tag;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can list tags', function () {
    Tag::factory(3)->create(['user_id' => $this->user->id]);

    $response = $this->get(route('tags.index'));

    $response->assertStatus(200);
});

it('can create a tag', function () {
    $response = $this->post(route('tags.store'), [
        'name' => 'urgent',
        'color' => 'red',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('tags', [
        'name' => 'urgent',
        'user_id' => $this->user->id,
    ]);
});

it('can update a tag', function () {
    $tag = Tag::factory()->create(['user_id' => $this->user->id, 'name' => 'old-name']);

    $response = $this->put(route('tags.update', $tag), [
        'name' => 'new-name',
        'color' => 'blue',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('tags', [
        'id' => $tag->id,
        'name' => 'new-name',
    ]);
});

it('cannot update another users tag', function () {
    $other = User::factory()->create();
    $tag = Tag::factory()->create(['user_id' => $other->id]);

    $response = $this->put(route('tags.update', $tag), [
        'name' => 'hacked',
    ]);

    $response->assertStatus(403);
});

it('can delete a tag', function () {
    $tag = Tag::factory()->create(['user_id' => $this->user->id]);

    $response = $this->delete(route('tags.destroy', $tag));

    $response->assertRedirect();
    $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
});

it('cannot delete another users tag', function () {
    $other = User::factory()->create();
    $tag = Tag::factory()->create(['user_id' => $other->id]);

    $response = $this->delete(route('tags.destroy', $tag));

    $response->assertStatus(403);
});

it('can show a tag', function () {
    $tag = Tag::factory()->create(['user_id' => $this->user->id, 'name' => 'important']);

    $response = $this->get(route('tags.show', $tag));

    $response->assertStatus(200);
    $response->assertSee('important');
});

it('cannot show another users tag', function () {
    $other = User::factory()->create();
    $tag = Tag::factory()->create(['user_id' => $other->id]);

    $response = $this->get(route('tags.show', $tag));

    $response->assertStatus(403);
});
