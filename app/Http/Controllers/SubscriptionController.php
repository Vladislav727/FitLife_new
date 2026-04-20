<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function store(Request $request, User $user)
    {
        $me = Auth::user();

        if ($me->id === $user->id) {
            return response()->json(['error' => 'Cannot subscribe to yourself'], 400);
        }

        if ($me->hasSubscriptionWith($user) || $me->hasPendingSubscriptionTo($user)) {
            return response()->json(['error' => 'Subscription request already sent or user is already subscribed'], 400);
        }

        Subscription::create([
            'user_id' => $me->id,
            'subscribed_user_id' => $user->id,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'Subscription request sent!',
            'action' => 'request_sent',
            'addAction' => route('subscriptions.store', $user),
            'removeAction' => route('subscriptions.remove', $user),
        ], 201);
    }

    public function accept(Request $request, User $user)
    {
        $subscriptionRequest = Subscription::where('user_id', $user->id)
            ->where('subscribed_user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if (! $subscriptionRequest) {
            return response()->json(['error' => 'No pending subscription request found'], 404);
        }

        $subscriptionRequest->update(['status' => 'accepted']);

        Subscription::create([
            'user_id' => Auth::id(),
            'subscribed_user_id' => $user->id,
            'status' => 'accepted',
        ]);

        return response()->json([
            'status' => 'Subscription request accepted!',
            'action' => 'accepted',
            'removeAction' => route('subscriptions.remove', $user),
        ], 200);
    }

    public function remove(Request $request, User $user)
    {
        $subscription = Subscription::where(function ($query) use ($user) {
            $query->where('user_id', Auth::id())
                ->where('subscribed_user_id', $user->id)
                ->orWhere(function ($nestedQuery) use ($user) {
                    $nestedQuery->where('user_id', $user->id)
                        ->where('subscribed_user_id', Auth::id());
                });
        })->where('status', 'accepted');

        if ($subscription->count() === 0) {
            return response()->json(['error' => 'Subscription not found'], 404);
        }

        $subscription->delete();

        return response()->json([
            'status' => 'Subscription removed',
            'action' => 'removed',
            'addAction' => route('subscriptions.store', $user),
        ], 200);
    }
}