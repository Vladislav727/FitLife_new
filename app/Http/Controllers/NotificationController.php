<?php

namespace App\Http\Controllers;

use App\Models\GroupInvite;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $invites = GroupInvite::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with(['group', 'sender'])
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('invites'));
    }

    public function acceptInvite(GroupInvite $invite)
    {
        if ($invite->user_id !== Auth::id()) {
            abort(403);
        }

        $invite->update(['status' => 'accepted']);
        $invite->group->members()->attach(Auth::id(), ['role' => 'member']);

        return back()->with('success', __('messages.invite_accepted'));
    }

    public function declineInvite(GroupInvite $invite)
    {
        if ($invite->user_id !== Auth::id()) {
            abort(403);
        }

        $invite->update(['status' => 'declined']);

        return back()->with('success', __('messages.invite_declined'));
    }
}
