<?php

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('guests cannot access posts', function () {
    $response = $this->get('/posts');

    $response->assertRedirect('/login');
});

test('authenticated users can access posts', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/posts');

    $response->assertOk();
});

test('users can create text posts', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/posts', [
        'content' => 'This is my first post!',
    ]);

    $response->assertOk();
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('posts', [
        'user_id' => $user->id,
        'content' => 'This is my first post!',
    ]);
});

test('users can create posts with images', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $response = $this->actingAs($user)->withHeader('Accept', 'application/json')->post('/posts', [
        'content' => 'Post with image',
        'photo' => UploadedFile::fake()->image('photo.jpg'),
    ]);

    $response->assertOk();
    $response->assertJson(['success' => true]);

    $post = Post::where('user_id', $user->id)->first();
    expect($post->media_type)->toBe('image');
    expect($post->media_path)->not->toBeNull();
});

test('post content is optional if media is provided', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $response = $this->actingAs($user)->withHeader('Accept', 'application/json')->post('/posts', [
        'photo' => UploadedFile::fake()->image('photo.jpg'),
    ]);

    $response->assertOk();
    $response->assertJson(['success' => true]);
});

test('post content must not exceed 1000 characters', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/posts', [
        'content' => str_repeat('a', 1001),
    ]);

    $response->assertStatus(422);
});

test('users can update their own posts', function () {
    $user = User::factory()->create();

    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Original content',
        'views' => 0,
    ]);

    $response = $this->actingAs($user)->putJson("/posts/{$post->id}", [
        'content' => 'Updated content',
    ]);

    $response->assertOk();
    $response->assertJson(['success' => true]);

    $post->refresh();
    expect($post->content)->toBe('Updated content');
});

test('users cannot update other users posts', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $post = Post::create([
        'user_id' => $user1->id,
        'content' => 'Original content',
        'views' => 0,
    ]);

    $response = $this->actingAs($user2)->putJson("/posts/{$post->id}", [
        'content' => 'Hacked content',
    ]);

    $response->assertStatus(403);
});

test('users can delete their own posts', function () {
    $user = User::factory()->create();

    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Post to delete',
        'views' => 0,
    ]);

    $response = $this->actingAs($user)->deleteJson("/posts/{$post->id}");

    $response->assertJson(['success' => true]);
    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});

test('users cannot delete other users posts', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $post = Post::create([
        'user_id' => $user1->id,
        'content' => 'Protected post',
        'views' => 0,
    ]);

    $response = $this->actingAs($user2)->deleteJson("/posts/{$post->id}");

    $response->assertStatus(403);
    $this->assertDatabaseHas('posts', ['id' => $post->id]);
});

test('users can like posts', function () {
    $user = User::factory()->create();

    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Likeable post',
        'views' => 0,
    ]);

    $response = $this->actingAs($user)->post("/posts/{$post->id}/reaction", [
        'type' => 'like',
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('likes', [
        'user_id' => $user->id,
        'post_id' => $post->id,
        'type' => 'post',
        'is_like' => true,
    ]);
});

test('users can dislike posts', function () {
    $user = User::factory()->create();

    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Dislikeable post',
        'views' => 0,
    ]);

    $response = $this->actingAs($user)->post("/posts/{$post->id}/reaction", [
        'type' => 'dislike',
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('likes', [
        'user_id' => $user->id,
        'post_id' => $post->id,
        'type' => 'post',
        'is_like' => false,
    ]);
});

test('users can toggle reactions', function () {
    $user = User::factory()->create();

    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Toggle reaction post',
        'views' => 0,
    ]);

    $this->actingAs($user)->post("/posts/{$post->id}/reaction", ['type' => 'like']);
    expect(Like::where('post_id', $post->id)->where('type', 'post')->where('is_like', true)->count())->toBe(1);

    $this->actingAs($user)->post("/posts/{$post->id}/reaction", ['type' => 'dislike']);
    expect(Like::where('post_id', $post->id)->where('type', 'post')->where('is_like', false)->count())->toBe(1);
    expect(Like::where('post_id', $post->id)->where('type', 'post')->where('is_like', true)->count())->toBe(0);
});

test('users can comment on posts', function () {
    $user = User::factory()->create();

    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Commentable post',
        'views' => 0,
    ]);

    $response = $this->actingAs($user)->postJson("/posts/{$post->id}/comment", [
        'content' => 'This is a comment!',
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('comments', [
        'user_id' => $user->id,
        'post_id' => $post->id,
        'content' => 'This is a comment!',
    ]);
});

test('posts display with like and comment counts', function () {
    $user = User::factory()->create();

    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Popular post',
        'views' => 0,
    ]);

    Like::create(['user_id' => $user->id, 'post_id' => $post->id, 'type' => 'post', 'is_like' => true]);

    Comment::create([
        'user_id' => $user->id,
        'post_id' => $post->id,
        'content' => 'Comment 1',
    ]);

    $response = $this->actingAs($user)->get('/posts');

    $response->assertOk();
});
