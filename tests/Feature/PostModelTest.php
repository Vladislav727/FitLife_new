<?php

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;

test('post belongs to user', function () {
    $user = User::factory()->create();
    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Test post',
        'views' => 0,
    ]);

    expect($post->user->id)->toBe($user->id);
});

test('post has comments relationship', function () {
    $user = User::factory()->create();
    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Test post',
        'views' => 0,
    ]);

    Comment::create([
        'user_id' => $user->id,
        'post_id' => $post->id,
        'content' => 'Test comment',
    ]);

    expect($post->comments)->toHaveCount(1);
});

test('post comments only returns top-level comments', function () {
    $user = User::factory()->create();
    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Test post',
        'views' => 0,
    ]);

    $parentComment = Comment::create([
        'user_id' => $user->id,
        'post_id' => $post->id,
        'content' => 'Parent comment',
    ]);

    Comment::create([
        'user_id' => $user->id,
        'post_id' => $post->id,
        'parent_id' => $parentComment->id,
        'content' => 'Reply comment',
    ]);

    expect($post->comments)->toHaveCount(1);
});

test('post allComments returns all comments including replies', function () {
    $user = User::factory()->create();
    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Test post',
        'views' => 0,
    ]);

    $parentComment = Comment::create([
        'user_id' => $user->id,
        'post_id' => $post->id,
        'content' => 'Parent comment',
    ]);

    Comment::create([
        'user_id' => $user->id,
        'post_id' => $post->id,
        'parent_id' => $parentComment->id,
        'content' => 'Reply comment',
    ]);

    expect($post->allComments)->toHaveCount(2);
});

test('post has likes relationship', function () {
    $user = User::factory()->create();
    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Test post',
        'views' => 0,
    ]);

    Like::create([
        'user_id' => $user->id,
        'post_id' => $post->id,
        'type' => 'post',
        'is_like' => true,
    ]);

    expect($post->likes)->toHaveCount(1);
});

test('post can have media path and type', function () {
    $user = User::factory()->create();
    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Post with media',
        'media_path' => 'posts/image.jpg',
        'media_type' => 'image',
        'views' => 0,
    ]);

    expect($post->media_path)->toBe('posts/image.jpg');
    expect($post->media_type)->toBe('image');
});

test('post views counter works', function () {
    $user = User::factory()->create();
    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Test post',
        'views' => 0,
    ]);

    expect($post->views)->toBe(0);

    $post->increment('views');
    $post->refresh();

    expect($post->views)->toBe(1);
});
