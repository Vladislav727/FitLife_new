@extends('layouts.app')

@section('title', 'Comments')

@section('content')
    @vite([        'resources/css/admin/admin.css',
        'resources/css/admin/comments.css',
        'resources/css/admin/adminposts.css',
    ])

    <div class="admin-layout admin-layout--comments">
        <section class="admin-hero admin-hero--warning">
            <div class="admin-hero__content">
                <span class="admin-hero__eyebrow">Comment moderation</span>

                <div>
                    <h1 class="admin-hero__title">Review comments</h1>
                    <p class="admin-hero__description">
                        Search the latest discussion, inspect the related post, and remove comments that violate community standards.
                    </p>
                </div>

                <div class="admin-hero__meta">
                    <span class="admin-badge admin-badge--warning">{{ number_format((int) ($comments->total() ?? $comments->count() ?? 0)) }} comments</span>
                    <span class="admin-badge admin-badge--muted">Moderation queue</span>
                </div>

                <div class="admin-hero__actions">
                    <a href="{{ route('admin.dashboard') }}" class="admin-button admin-button--ghost">Back to dashboard</a>
                    <a href="{{ route('admin.posts') }}" class="admin-button admin-button--secondary">Posts</a>
                </div>
            </div>
        </section>

        <section class="admin-toolbar">
            <div class="admin-toolbar__group">
                <label class="admin-toolbar__label" for="comment-search">Search</label>
                <input id="comment-search" type="search" class="admin-toolbar__search" placeholder="Search by author, post, or comment text" autocomplete="off">
            </div>

            <div class="admin-toolbar__group">
                <span class="admin-badge admin-badge--warning">Delete only</span>
            </div>
        </section>

        <div class="admin-empty-state" data-comments-empty hidden>
            <p class="admin-empty-state__title">No matching comments</p>
            <p class="admin-empty-state__description">Try a different search term to find a comment in the moderation queue.</p>
        </div>

        <section class="admin-card">
            <div class="admin-card__header">
                <div class="admin-card__title-group">
                    <h2 class="admin-card__title">Comments</h2>
                    <p class="admin-card__description">The table below shows the latest comments with their related user and post.</p>
                </div>
            </div>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Comment</th>
                            <th>Author</th>
                            <th>Post</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($comments as $comment)
                            @php
                                $commentText = data_get($comment, 'content')
                                    ?? data_get($comment, 'body')
                                    ?? data_get($comment, 'message')
                                    ?? '';
                                $authorName = data_get($comment, 'user.name', 'Unknown user');
                                $postTitle = data_get($comment, 'post.title', 'Unknown post');
                                $searchText = strtolower($authorName . ' ' . $postTitle . ' ' . strip_tags($commentText));
                            @endphp
                            <tr data-comment-row data-search-text="{{ $searchText }}">
                                <td data-label="Comment">
                                    <div class="admin-comment__context">
                                        <p class="admin-comment__excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($commentText), 160) ?: 'No comment text available' }}</p>
                                        <span class="admin-comment__author">#{{ $comment->id }}</span>
                                    </div>
                                </td>
                                <td data-label="Author">
                                    <div class="admin-comment__meta">
                                        <strong>{{ $authorName }}</strong>
                                        <span>{{ data_get($comment, 'user.email', 'No email available') }}</span>
                                    </div>
                                </td>
                                <td data-label="Post">
                                    <div class="admin-comment__context">
                                        <span>{{ $postTitle }}</span>
                                        <span class="admin-comment__author">Post ID #{{ data_get($comment, 'post.id', '—') }}</span>
                                    </div>
                                </td>
                                <td data-label="Created">
                                    <div class="admin-comment__meta">
                                        <strong>{{ optional(data_get($comment, 'created_at'))->format('M d, Y') ?? '—' }}</strong>
                                        <span>{{ optional(data_get($comment, 'created_at'))->diffForHumans() ?? 'Recently' }}</span>
                                    </div>
                                </td>
                                <td data-label="Actions">
                                    <div class="admin-comment__actions">
                                        <form action="{{ route('admin.comments.delete', $comment) }}" method="POST" onsubmit="return confirm('Delete this comment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-button admin-button--sm admin-button--danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="admin-empty-state">
                                        <p class="admin-empty-state__title">No comments found</p>
                                        <p class="admin-empty-state__description">There are no comments to review right now.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="admin-card__footer">
                <div class="admin-card__meta">
                    <span class="admin-badge admin-badge--muted">Total: {{ number_format((int) ($comments->total() ?? $comments->count() ?? 0)) }}</span>
                </div>
                {{ $comments->links() }}
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin-comments-index.js') }}" defer></script>
@endsection