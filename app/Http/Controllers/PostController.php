<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Post;
use App\Models\PostView;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = 10;
            $page = $request->input('page', 1);
            $sort = $request->input('sort', 'newest');
            $cacheKey = "posts_page_{$page}_sort_{$sort}";

            $posts = Cache::remember($cacheKey, 60, function () use ($perPage, $sort) {
                $query = Post::with(['user', 'comments' => function ($q) {
                    $q->whereNull('parent_id')->with(['replies.user', 'replies.replyTo.user', 'replies.parent.user', 'user', 'parent.user']);
                }, 'likes'])
                    ->withCount([
                        'likes as like_count' => fn ($q) => $q->where('type', 'like'),
                        'likes as dislike_count' => fn ($q) => $q->where('type', 'dislike'),
                        'allComments as comment_count',
                    ]);

                switch ($sort) {
                    case 'top':

                        $query->orderByRaw('(SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id AND likes.type = ? AND likes.is_like = 1) - (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id AND likes.type = ? AND likes.is_like = 0) DESC', ['post', 'post']);
                        break;
                    case 'hot':

                        $query->where('created_at', '>=', now()->subDays(7))
                            ->orderByRaw('((SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id AND likes.type = ? AND likes.is_like = 1) + (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id)) / (TIMESTAMPDIFF(HOUR, posts.created_at, NOW()) + 1) DESC', ['post']);
                        break;
                    case 'newest':
                    default:
                        $query->latest();
                        break;
                }

                return $query->paginate($perPage);
            });

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'posts' => $posts->items()->map(function ($post) {
                        return $this->formatPostForJson($post);
                    }),
                ]);
            }

            $trendingPosts = Post::withCount(['likes as like_count' => fn ($q) => $q->where('type', 'post')->where('is_like', true)])
                ->where('created_at', '>=', now()->subDays(7))
                ->orderByDesc('like_count')
                ->limit(5)
                ->with('user')
                ->get();

            $activeUsers = User::where('id', '!=', Auth::id())
                ->where('last_seen_at', '>=', now()->subMinutes(5))
                ->select('id', 'name', 'username', 'avatar', 'last_seen_at')
                ->limit(10)
                ->get();

            return view('posts.index', compact('posts', 'trendingPosts', 'activeUsers'));
        } catch (\Exception $e) {
            \Log::error('Failed to load posts: '.$e->getMessage());

            return back()->with('error', 'Failed to load posts: '.$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'content' => 'nullable|string|max:1000',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'video' => 'nullable|mimetypes:video/mp4,video/mpeg,video/ogg,video/webm|max:10240',
            ]);

            $media = $this->handleMediaUpload($request->file('photo'), $request->file('video'));

            $post = Post::create([
                'user_id' => Auth::id(),
                'content' => $request->input('content'),
                'media_path' => $media['path'],
                'media_type' => $media['type'],
                'views' => 0,
            ]);

            $this->clearPostsCache();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'post' => $this->formatPostResponse($post),
                ], 200);
            }

            return redirect()->route('posts.index')->with('success', __('toast.post_created'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Post creation validation failed: '.json_encode($e->errors()));
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: '.implode(', ', $e->errors()['photo'] ?? $e->errors()['video'] ?? ['Unknown error']),
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Post creation failed: '.$e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create post: '.$e->getMessage(),
                ], 500);
            }

            return back()->with('error', __('toast.post_create_error'))->withInput();
        }
    }

    public function update(Request $request, Post $post)
    {
        try {

            if (! Auth::user()->can('update', $post)) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
                }

                return back()->with('error', 'Unauthorized');
            }

            $request->validate([
                'content' => 'nullable|string|max:1000',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'video' => 'nullable|mimetypes:video/mp4,video/mpeg,video/ogg,video/webm|max:10240',
                'remove_media' => 'nullable|boolean',
            ]);

            $mediaPath = $post->media_path;
            $mediaType = $post->media_type;

            if ($request->hasFile('photo') || $request->hasFile('video')) {
                $this->deleteMedia($post->media_path);
                $media = $this->handleMediaUpload($request->file('photo'), $request->file('video'));
                $mediaPath = $media['path'];
                $mediaType = $media['type'];
            } elseif ($request->input('remove_media')) {
                $this->deleteMedia($post->media_path);
                $mediaPath = null;
                $mediaType = null;
            }

            $post->update([
                'content' => $request->input('content'),
                'media_path' => $mediaPath,
                'media_type' => $mediaType,
            ]);

            $this->clearPostsCache();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'post' => [
                        'id' => $post->id,
                        'content' => $post->content,
                        'media_path' => $post->media_path,
                        'media_type' => $post->media_type,
                    ],
                ], 200);
            }

            return redirect()->route('posts.index')->with('success', __('toast.post_updated'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Post update validation failed: '.json_encode($e->errors()));
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: '.implode(', ', $e->errors()['photo'] ?? $e->errors()['video'] ?? ['Unknown error']),
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Post update failed: '.$e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update post: '.$e->getMessage(),
                ], 500);
            }

            return back()->with('error', __('toast.post_update_error'))->withInput();
        }
    }

    public function destroy(Request $request, Post $post)
    {
        try {

            if (! Auth::user()->can('delete', $post)) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
                }

                return back()->with('error', 'Unauthorized');
            }

            $this->deleteMedia($post->media_path);
            $post->delete();
            $this->clearPostsCache();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Post deleted successfully'], 200);
            }

            return redirect()->route('posts.index')->with('success', __('toast.post_deleted'));
        } catch (\Exception $e) {
            \Log::error('Post deletion failed: '.$e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete post: '.$e->getMessage(),
                ], 500);
            }

            return back()->with('error', __('toast.post_delete_error'));
        }
    }

    public function toggleReaction(Request $request, Post $post)
    {
        try {
            if (! Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $request->validate(['type' => 'required|in:like,dislike']);
            $userId = Auth::id();
            $isLike = $request->input('type') === 'like';
            $existing = Like::where('post_id', $post->id)
                ->where('user_id', $userId)
                ->where('type', 'post')
                ->first();
            $resultType = $request->input('type');

            if ($existing && $existing->is_like === $isLike) {

                $existing->delete();
                $resultType = null;
            } else {

                Like::updateOrCreate(
                    ['post_id' => $post->id, 'user_id' => $userId, 'type' => 'post'],
                    ['is_like' => $isLike]
                );

                // Notify post owner about like (not self)
                if ($isLike && $post->user_id !== $userId) {
                    Notification::firstOrCreate([
                        'user_id' => $post->user_id,
                        'sender_id' => $userId,
                        'type' => 'like',
                        'notifiable_type' => Post::class,
                        'notifiable_id' => $post->id,
                    ]);
                }
            }

            Cache::forget("post_{$post->id}_like_count");
            Cache::forget("post_{$post->id}_dislike_count");

            return response()->json([
                'success' => true,
                'type' => $resultType,
                'likeCount' => $post->likes()->where('type', 'post')->where('is_like', true)->count(),
                'dislikeCount' => $post->likes()->where('type', 'post')->where('is_like', false)->count(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Reaction toggle failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle reaction: '.$e->getMessage(),
            ], 500);
        }
    }

    public function comment(Request $request, Post $post)
    {
        try {
            $request->validate([
                'content' => 'required|string|max:500',
                'parent_id' => 'nullable|exists:comments,id',
                'reply_to_id' => 'nullable|exists:comments,id',
            ]);

            // TikTok-style: flatten to root comment (max 1 level of nesting)
            $parentId = $request->input('parent_id');
            if ($parentId) {
                $parentComment = Comment::find($parentId);
                if ($parentComment && $parentComment->parent_id) {
                    $parentId = $parentComment->parent_id;
                }
            }

            $existingComment = Comment::where([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'content' => $request->input('content'),
                'parent_id' => $parentId,
            ])->first();

            if ($existingComment) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'comment' => $this->formatCommentForJson($existingComment),
                    ], 200);
                }

                return back()->with('success', __('toast.comment_added'));
            }

            $comment = Comment::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'parent_id' => $parentId,
                'reply_to_id' => $request->input('reply_to_id'),
                'content' => $request->input('content'),
            ]);

            $comment->load('parent.user', 'replyTo.user');

            // Notify post owner about comment (not self)
            if ($post->user_id !== Auth::id()) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'sender_id' => Auth::id(),
                    'type' => 'comment',
                    'notifiable_type' => Post::class,
                    'notifiable_id' => $post->id,
                ]);
            }

            // Notify @mentioned users
            if (preg_match('/^@(\S+)/', $request->input('content'), $matches)) {
                $mentionedUser = User::where('username', $matches[1])->first();
                if ($mentionedUser && $mentionedUser->id !== Auth::id()) {
                    Notification::create([
                        'user_id' => $mentionedUser->id,
                        'sender_id' => Auth::id(),
                        'type' => 'mention',
                        'notifiable_type' => Comment::class,
                        'notifiable_id' => $comment->id,
                    ]);
                }
            }

            Cache::forget('posts_page_1');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'comment' => $this->formatCommentForJson($comment),
                ], 200);
            }

            return back()->with('success', __('toast.comment_added'));
        } catch (\Exception $e) {
            \Log::error('Comment creation failed: '.$e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add comment: '.$e->getMessage(),
                ], 500);
            }

            return back()->with('error', __('toast.comment_add_error'));
        }
    }

    public function incrementViews(Request $request, Post $post)
    {
        try {
            if (! Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $userId = Auth::id();

            $alreadyViewed = PostView::where('post_id', $post->id)
                ->where('user_id', $userId)
                ->exists();

            if (! $alreadyViewed) {

                PostView::create([
                    'post_id' => $post->id,
                    'user_id' => $userId,
                ]);

                $post->views = PostView::where('post_id', $post->id)->count();
                $post->save();
            }

            return response()->json([
                'success' => true,
                'views' => PostView::where('post_id', $post->id)->count(),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('View increment failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to increment views: '.$e->getMessage(),
            ], 500);
        }
    }

    public function bulkViews(Request $request)
    {
        try {
            $postIds = $request->input('post_ids', []);

            if (empty($postIds)) {
                return response()->json(['views' => []], 200);
            }

            $views = PostView::selectRaw('post_id, COUNT(*) as count')
                ->whereIn('post_id', $postIds)
                ->groupBy('post_id')
                ->pluck('count', 'post_id')
                ->toArray();

            $result = [];
            foreach ($postIds as $postId) {
                $result[$postId] = $views[$postId] ?? 0;
            }

            return response()->json(['views' => $result], 200);
        } catch (\Exception $e) {
            \Log::error('Bulk views failed: '.$e->getMessage());

            return response()->json(['views' => []], 500);
        }
    }

    public function bulkStats(Request $request)
    {
        try {
            $postIds = $request->input('post_ids', []);

            if (empty($postIds)) {
                return response()->json(['posts' => []], 200);
            }

            $posts = Post::with(['allComments'])->whereIn('id', $postIds)->get();
            $userId = Auth::id();

            $result = [];
            foreach ($posts as $post) {
                $result[$post->id] = [
                    'views' => $post->postViews()->count(),
                    'likes' => $post->likes()->where('type', 'post')->where('is_like', true)->count(),
                    'dislikes' => $post->likes()->where('type', 'post')->where('is_like', false)->count(),
                    'comments' => $post->allComments()->count(),
                    'content' => $post->content,
                    'media_path' => $post->media_path ? asset('storage/'.$post->media_path) : null,
                    'media_type' => $post->media_type,
                    'updated_at' => $post->updated_at->toISOString(),
                    'user_liked' => $userId ? $post->isLikedBy($userId) : false,
                    'user_disliked' => $userId ? $post->isDislikedBy($userId) : false,
                ];
            }

            return response()->json(['posts' => $result], 200);
        } catch (\Exception $e) {
            \Log::error('Bulk stats failed: '.$e->getMessage());

            return response()->json(['posts' => []], 500);
        }
    }

    private function handleMediaUpload($photo, $video)
    {
        if ($photo) {
            $filename = uniqid('post_').'.'.$photo->getClientOriginalExtension();
            $path = $photo->storeAs('posts', $filename, 'public');

            return ['path' => $path, 'type' => 'image'];
        } elseif ($video) {
            $filename = uniqid('post_').'.'.$video->getClientOriginalExtension();
            $path = $video->storeAs('posts', $filename, 'public');

            return ['path' => $path, 'type' => 'video'];
        }

        return ['path' => null, 'type' => null];
    }

    private function deleteMedia($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function formatPostResponse($post)
    {
        $user = Auth::user();

        return [
            'id' => $post->id,
            'content' => $post->content,
            'media_path' => $post->media_path,
            'media_type' => $post->media_type,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'username' => $user->username,
                'profile_url' => route('profile.show', $user->username),
            ],
            'created_at_diff' => $post->created_at->diffForHumans(),
            'views' => $post->views,
            'like_count' => $post->likes()->where('type', 'like')->count(),
            'dislike_count' => $post->likes()->where('type', 'dislike')->count(),
            'comment_count' => $post->allComments()->count(),
            'user_liked' => $post->isLikedBy(Auth::id()),
            'user_disliked' => $post->isDislikedBy(Auth::id()),
            'can_update' => Auth::id() === $post->user_id,
            'can_delete' => Auth::id() === $post->user_id,
        ];
    }

    private function formatPostForJson($post)
    {
        return [
            'id' => $post->id,
            'content' => $post->content,
            'media_path' => $post->media_path,
            'media_type' => $post->media_type,
            'user' => [
                'name' => $post->user->name,
                'username' => $post->user->username,
                'avatar' => $post->user->avatar,
                'profile_url' => route('profile.show', $post->user->username),
            ],
            'created_at_diff' => $post->created_at->diffForHumans(),
            'views' => $post->views,
            'like_count' => $post->like_count ?? $post->likes()->where('type', 'like')->count(),
            'dislike_count' => $post->dislike_count ?? $post->likes()->where('type', 'dislike')->count(),
            'comment_count' => $post->comment_count ?? $post->allComments()->count(),
            'user_liked' => $post->isLikedBy(Auth::id()),
            'user_disliked' => $post->isDislikedBy(Auth::id()),
            'can_update' => Auth::id() === $post->user_id,
            'can_delete' => Auth::id() === $post->user_id,
            'comments' => $post->allComments->map(function ($comment) {
                return $this->formatCommentForJson($comment);
            })->toArray(),
        ];
    }

    private function formatCommentForJson($comment)
    {
        $comment->load('parent.user', 'replyTo.user');

        // Use replyTo (actual comment replied to) for quote, fall back to parent
        $quoted = $comment->replyTo ?: $comment->parent;

        return [
            'id' => $comment->id,
            'content' => $comment->content,
            'user_id' => $comment->user_id,
            'user_name' => $comment->user->name,
            'user_avatar' => $comment->user->avatar,
            'user' => [
                'username' => $comment->user->username,
                'profile_url' => route('profile.show', $comment->user->username),
            ],
            'created_at_diff' => $comment->created_at->diffForHumans(),
            'parent_id' => $comment->parent_id,
            'reply_to_id' => $comment->reply_to_id,
            'quoted_name' => $quoted ? $quoted->user->name : null,
            'quoted_username' => $quoted ? $quoted->user->username : null,
            'quoted_content' => $quoted ? $quoted->content : null,
            'quoted_comment_id' => $quoted ? $quoted->id : null,
            'reply_count' => $comment->replies()->count(),
            'like_count' => $comment->likes()->where('type', 'like')->count(),
            'dislike_count' => $comment->likes()->where('type', 'dislike')->count(),
            'user_liked' => $comment->likes()->where('user_id', Auth::id())->where('type', 'like')->exists(),
            'user_disliked' => $comment->likes()->where('user_id', Auth::id())->where('type', 'dislike')->exists(),
            'can_update' => Auth::id() === $comment->user_id,
            'can_delete' => Auth::id() === $comment->user_id,
        ];
    }

    private function clearPostsCache(int $page = 1)
    {
        $sorts = ['newest', 'top', 'hot'];

        foreach ($sorts as $sort) {
            Cache::forget("posts_page_{$page}_sort_{$sort}");
        }

        Cache::forget("posts_page_{$page}");
    }

    public function searchUsers(Request $request)
    {
        $q = $request->input('q', '');
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $users = User::where('id', '!=', Auth::id())
            ->where(function ($query) use ($q) {
                $query->where('name', 'LIKE', "%{$q}%")
                      ->orWhere('username', 'LIKE', "%{$q}%");
            })
            ->select('id', 'name', 'username', 'avatar', 'last_seen_at')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/logo/defaultPhoto.jpg'),
                    'online' => $user->isOnline(),
                    'url' => route('profile.show', $user->username),
                ];
            });

        return response()->json($users);
    }
}
