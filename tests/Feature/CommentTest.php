<?php

use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Post;
use App\Models\User;

test('guests cannot access comment endpoints', function () {
    $user = User::factory()->create();
    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Test post',
        'views' => 0,
    ]);
    $comment = Comment::create([
        'user_id' => $user->id,
        'post_id' => $post->id,
        'content' => 'Test comment',
    ]);

    $response = $this->put("/comments/{$comment->id}", [
        'content' => 'Updated',
    ]);

    $response->assertRedirect('/login');
});

test('users can update their own comments', function () {
    $user = User::factory()->create();
    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Test post',
        'views' => 0,
    ]);
    $comment = Comment::create([
        'user_id' => $user->id,
        'post_id' => $post->id,
        'content' => 'Original comment',
    ]);

    $response = $this->actingAs($user)->putJson("/comments/{$comment->id}", [
        'content' => 'Updated comment',
    ]);

    $response->assertOk();
    $response->assertJson(['success' => true]);

    $comment->refresh();
    expect($comment->content)->toBe('Updated comment');
});

test('users can delete their own comments', function () {
    $user = User::factory()->create();
    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Test post',
        'views' => 0,
    ]);
    $comment = Comment::create([
        'user_id' => $user->id,
        'post_id' => $post->id,
        'content' => 'Comment to delete',
    ]);

    $response = $this->actingAs($user)->deleteJson("/comments/{$comment->id}");

    $response->assertOk();
    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});

test('users can like comments', function () {
    $user = User::factory()->create();
    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Test post',
        'views' => 0,
    ]);
    $comment = Comment::create([
        'user_id' => $user->id,
        'post_id' => $post->id,
        'content' => 'Comment to like',
    ]);

    $response = $this->actingAs($user)->post("/comments/{$comment->id}/toggle-reaction", [
        'type' => 'like',
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('comment_likes', [
        'user_id' => $user->id,
        'comment_id' => $comment->id,
        'type' => 'like',
    ]);
});

test('users can dislike comments', function () {
    $user = User::factory()->create();
    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Test post',
        'views' => 0,
    ]);
    $comment = Comment::create([
        'user_id' => $user->id,
        'post_id' => $post->id,
        'content' => 'Comment to dislike',
    ]);

    $response = $this->actingAs($user)->post("/comments/{$comment->id}/toggle-reaction", [
        'type' => 'dislike',
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('comment_likes', [
        'user_id' => $user->id,
        'comment_id' => $comment->id,
        'type' => 'dislike',
    ]);
});

test('users can toggle comment reactions', function () {
    $user = User::factory()->create();
    $post = Post::create([
        'user_id' => $user->id,
        'content' => 'Test post',
        'views' => 0,
    ]);
    $comment = Comment::create([
        'user_id' => $user->id,
        'post_id' => $post->id,
        'content' => 'Comment for toggle',
    ]);

    $this->actingAs($user)->post("/comments/{$comment->id}/toggle-reaction", [
        'type' => 'like',
    ]);

    expect(CommentLike::where('comment_id', $comment->id)->where('type', 'like')->count())->toBe(1);

    $this->actingAs($user)->post("/comments/{$comment->id}/toggle-reaction", [
        'type' => 'dislike',
    ]);

    expect(CommentLike::where('comment_id', $comment->id)->where('type', 'dislike')->count())->toBe(1);
    expect(CommentLike::where('comment_id', $comment->id)->where('type', 'like')->count())->toBe(0);
});

test('comment replies are properly nested', function () {
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

    $replyComment = Comment::create([
        'user_id' => $user->id,
        'post_id' => $post->id,
        'parent_id' => $parentComment->id,
        'content' => 'Reply to parent',
    ]);

    expect($replyComment->parent->id)->toBe($parentComment->id);
    expect($parentComment->replies)->toHaveCount(1);
    expect($parentComment->replies->first()->id)->toBe($replyComment->id);
});

test('deleting parent comment also deletes replies', function () {
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

    $replyComment = Comment::create([
        'user_id' => $user->id,
        'post_id' => $post->id,
        'parent_id' => $parentComment->id,
        'content' => 'Reply to parent',
    ]);

    $parentId = $parentComment->id;
    $replyId = $replyComment->id;

    $this->actingAs($user)->delete("/comments/{$parentComment->id}");

    expect(Comment::find($parentId))->toBeNull();
});
