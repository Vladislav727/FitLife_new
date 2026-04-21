<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminPanelController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalPosts = Post::count();
        $totalEvents = Calendar::count();
        $activeUsers = User::where('updated_at', '>=', now()->subDays(30))->count();
        $recentPosts = Post::with('user')->latest()->take(10)->get();
        $recentEvents = Calendar::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPosts',
            'totalEvents',
            'activeUsers',
            'recentPosts',
            'recentEvents'
        ));
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

    public function statistics()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('updated_at', '>=', now()->subDays(30))->count();

        return view('admin.statistics', compact('totalUsers', 'activeUsers'));
    }
}
