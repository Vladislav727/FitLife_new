<div class="comment-item {{ $comment->parent_id ? 'is-reply' : '' }} {{ ($hidden ?? false) ? 'reply-extra' : '' }}" id="comment-{{ $comment->id }}" data-comment-id="{{ $comment->id }}" data-root-id="{{ $comment->parent_id ?: $comment->id }}"@if($hidden ?? false) style="display:none"@endif>
    <img src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : asset('storage/default-avatar/default-avatar.avif') }}"
         alt="{{ $comment->user->name }}" class="comment-avatar">

    <div class="comment-content">
        <div class="comment-header">
            <a href="{{ route('profile.show', $comment->user->username) }}" class="comment-author">{{ $comment->user->name }}</a>
            <span class="comment-username">{{ '@' . $comment->user->username }}</span>
            <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
            @if($comment->updated_at->gt($comment->created_at))
                <span class="comment-edited" id="comment-edited-{{ $comment->id }}">· {{ __('posts.edited') }}</span>
            @else
                <span class="comment-edited" id="comment-edited-{{ $comment->id }}" style="display:none">· {{ __('posts.edited') }}</span>
            @endif
        </div>

        <div class="comment-text" id="comment-text-{{ $comment->id }}">
            @php
                $quoted = $comment->replyTo ?: $comment->parent;
            @endphp
            @if($comment->parent_id && $quoted)
                <div class="comment-quote" data-quoted-id="{{ $quoted->id }}" onclick="document.getElementById('comment-{{ $quoted->id }}')?.scrollIntoView({behavior:'smooth', block:'center'})">
                    <span class="comment-quote-author">{{ $quoted->user->name }} <span class="comment-quote-username">{{ '@' . $quoted->user->username }}</span></span>
                    <span class="comment-quote-text">{{ Str::limit($quoted->content, 100) }}</span>
                </div>
            @endif
            <p>{!! $comment->parent_id ? preg_replace('/(^@\S+)/', '<span class="comment-mention">$1</span>', e($comment->content)) : e($comment->content) !!}</p>
        </div>

        <form id="edit-comment-form-{{ $comment->id }}" class="comment-edit-form" action="{{ route('comments.update', $comment) }}" method="POST" style="display: none;">
            @csrf
            @method('PUT')
            <textarea name="content" maxlength="500">{{ $comment->content }}</textarea>
            <div class="comment-edit-btns">
                <button type="submit" class="btn-save">{{ __('posts.save') }}</button>
                <button type="button" class="btn-cancel cancel-edit-comment" data-comment-id="{{ $comment->id }}">{{ __('posts.cancel') }}</button>
            </div>
        </form>

        <div class="comment-actions">
            <button class="comment-btn like {{ Auth::check() && $comment->likes()->where('user_id', Auth::id())->where('type', 'like')->exists() ? 'active' : '' }}" data-comment-id="{{ $comment->id }}">
                <svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                <span>{{ $comment->likes()->where('type', 'like')->count() }}</span>
            </button>

            <button class="comment-btn dislike {{ Auth::check() && $comment->likes()->where('user_id', Auth::id())->where('type', 'dislike')->exists() ? 'active' : '' }}" data-comment-id="{{ $comment->id }}">
                <svg viewBox="0 0 24 24"><path d="M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm4 0v12h4V3h-4z"/></svg>
                <span>{{ $comment->likes()->where('type', 'dislike')->count() }}</span>
            </button>

            <button class="comment-btn reply reply-btn" data-comment-id="{{ $comment->parent_id ?: $comment->id }}" data-reply-to-id="{{ $comment->id }}" data-reply-to-user="{{ $comment->user->username }}" data-post-id="{{ $post->id }}">
                <svg viewBox="0 0 24 24"><path d="M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z"/></svg>
                <span>{{ __('posts.reply') }}</span>
            </button>

            @if (Auth::check() && Auth::id() === $comment->user_id)
                <button class="comment-btn edit edit-comment-btn" data-comment-id="{{ $comment->id }}">
                    <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34a.9959.9959 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                    <span>{{ __('posts.edit') }}</span>
                </button>
                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline-delete">
                    @csrf @method('DELETE')
                    <button type="submit" class="comment-btn delete">
                        <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                        <span>{{ __('posts.delete') }}</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
