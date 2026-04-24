@extends('layouts.app')

@section('title', 'Posts')

@section('content')
    @vite([        'resources/css/admin/admin.css',
        'resources/css/admin/adminposts.css',
    ])

    <div class="admin-layout admin-layout--posts">
        <section class="admin-hero">
            <div class="admin-hero__content">
                <span class="admin-hero__eyebrow">Content moderation</span>

                <div>
                    <h1 class="admin-hero__title">Review posts</h1>
                    <p class="admin-hero__description">
                        Search recent content, review authorship, and remove problematic posts when necessary.
                    </p>
                </div>

                <div class="admin-hero__meta">
                    <span class="admin-badge admin-badge--primary">{{ number_format((int) ($posts->total() ?? $posts->count() ?? 0)) }} posts</span>
                    <span class="admin-badge admin-badge--muted">Moderation ready</span>
                </div>

                <div class="admin-hero__actions">
                    <a href="{{ route('admin.dashboard') }}" class="admin-button admin-button--ghost">Back to dashboard</a>
                    <a href="{{ route('admin.comments') }}" class="admin-button admin-button--secondary">Comments</a>
                </div>
            </div>
        </section>

        <section class="admin-toolbar">
            <div class="admin-toolbar__group">
                <label class="admin-toolbar__label" for="post-search">Search</label>
                <input id="post-search" type="search" class="admin-toolbar__search" placeholder="Search posts by title or author" autocomplete="off">
            </div>

            <div class="admin-toolbar__group">
                <span class="admin-badge admin-badge--info">Delete actions only</span>
            </div>
        </section>

        <section class="admin-card">
            <div class="admin-card__header">
                <div class="admin-card__title-group">
                    <h2 class="admin-card__title">All posts</h2>
                    <p class="admin-card__description">Use the search box to quickly locate content.</p>
                </div>
            </div>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Post</th>
                            <th>Author</th>
                            <th>Published</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($posts as $post)
                            @php
                                $authorName = data_get($post, 'user.name', data_get($post, 'author.name', 'Unknown author'));
                                $searchText = strtolower(($post->title ?? '') . ' ' . $authorName);
                            @endphp
                            <tr data-post-row data-search-text="{{ $searchText }}">
                                <td data-label="Post">
                                    <div class="admin-table__stack">
                                        <strong class="admin-table__title">{{ $post->title ?? 'Untitled post' }}</strong>
                                        <span class="admin-table__subtitle">{{ \Illuminate\Support\Str::limit(strip_tags(data_get($post, 'content', data_get($post, 'body', ''))), 110) }}</span>
                                    </div>
                                </td>
                                <td data-label="Author">
                                    <div class="admin-table__stack">
                                        <span class="admin-table__meta">{{ $authorName }}</span>
                                        <span class="admin-table__footnote">ID #{{ $post->user_id ?? '—' }}</span>
                                    </div>
                                </td>
                                <td data-label="Published">
                                    <div class="admin-table__stack">
                                        <span class="admin-table__meta">{{ optional(data_get($post, 'created_at'))->format('M d, Y') ?? '—' }}</span>
                                        <span class="admin-table__footnote">{{ optional(data_get($post, 'created_at'))->diffForHumans() ?? 'Recently' }}</span>
                                    </div>
                                </td>
                                <td data-label="Actions">
                                    <div class="admin-table__actions">
                                        <form action="{{ route('admin.posts.delete', $post) }}" method="POST" onsubmit="return confirm('Delete this post?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-button admin-button--sm admin-button--danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="admin-empty-state">
                                        <p class="admin-empty-state__title">No posts found</p>
                                        <p class="admin-empty-state__description">There are no posts available for moderation right now.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="admin-card__footer">
                <div class="admin-card__meta">
                    <span class="admin-badge admin-badge--muted">Total: {{ number_format((int) ($posts->total() ?? $posts->count() ?? 0)) }}</span>
                </div>
                {{ $posts->links() }}
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin-posts-index.js') }}" defer></script>
@endsection