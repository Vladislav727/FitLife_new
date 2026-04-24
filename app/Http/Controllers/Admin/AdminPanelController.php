<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminPanelController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::count();
        $totalPosts = Post::count();
        $totalEvents = Calendar::count();
        $totalComments = Comment::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalSuperAdmins = User::where('role', 'super_admin')->count();
        $activeUsers = User::where('updated_at', '>=', now()->subDays(30))->count();
        $recentPosts = Post::with('user')->latest()->paginate(3, ['*'], 'posts_page');
        $recentComments = Comment::with(['user', 'post'])->latest()->paginate(4, ['*'], 'comments_page');

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPosts',
            'totalEvents',
            'totalComments',
            'totalAdmins',
            'totalSuperAdmins',
            'activeUsers',
            'recentPosts',
            'recentComments'
        ));
    }

    public function comments(): View
    {
        $comments = Comment::with(['user', 'post'])->latest()->paginate(10);

        return view('admin.comments.index', compact('comments'));
    }

    public function commentsDelete(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return redirect()->route('admin.comments')->with('success', 'Comment deleted successfully');
    }

    public function administrators(): View
    {
        $administrators = User::whereIn('role', ['admin', 'super_admin'])->latest()->paginate(10);

        return view('admin.administrators.index', compact('administrators'));
    }

    public function users()
    {
        $users = User::paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function usersShow(User $user)
    {
        $subscriptions = collect();
        $subscriptionsCount = 0;

        if (Schema::hasTable('subscriptions')) {
            $subscriptions = $user->subscriptions()->get();
            $subscriptionsCount = $subscriptions->count();
        }

        return view('admin.users.show', compact('user', 'subscriptions', 'subscriptionsCount'));
    }

    public function usersEdit(User $user)
    {
        if ($user->isSuperAdmin() && ! auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        return view('admin.users.edit', compact('user'));
    }

    public function usersUpdate(Request $request, User $user)
    {
        if ($user->isSuperAdmin() && ! auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $allowedRoles = auth()->user()->isSuperAdmin()
            ? 'in:user,admin,super_admin'
            : 'in:user,admin';

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|'.$allowedRoles,
        ]);

        if (! auth()->user()->isSuperAdmin() && isset($validated['role']) && $validated['role'] !== $user->role) {
            unset($validated['role']);
        }

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'User updated successfully');
    }

    public function usersDelete(User $user)
    {
        if ($user->isSuperAdmin()) {
            abort(403);
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }

    public function posts()
    {
        $posts = Post::with('user')->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    public function postsDelete(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts')->with('success', 'Post deleted successfully');
    }

    public function events()
    {
        $events = Calendar::with('user')->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    public function eventsDelete(Calendar $event)
    {
        $event->delete();

        return redirect()->route('admin.events')->with('success', 'Event deleted successfully');
    }

    public function statistics(): View
    {
        $totalUsers = User::count();
        $activeUsers = User::where('updated_at', '>=', now()->subDays(30))->count();
        $userStats = $this->buildMonthlyStats(User::class);
        $postStats = $this->buildMonthlyStats(Post::class);

        return view('admin.statistics', compact(
            'totalUsers',
            'activeUsers',
            'userStats',
            'postStats'
        ));
    }

    /**
     * Build monthly statistics for chart.js.
     *
     * @param  class-string  $modelClass
     * @return array<int, array{month:int, count:int}>
     */
    private function buildMonthlyStats(string $modelClass): array
    {
        $year = now()->year;

        $counts = $modelClass::query()
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month');

        return collect(range(1, 12))
            ->map(static fn (int $month): array => [
                'month' => $month,
                'count' => (int) ($counts[$month] ?? 0),
            ])
            ->all();
    }
}
