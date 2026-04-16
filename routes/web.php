<?php

use App\Http\Controllers\BiographyController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CalorieCalculatorController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressPhotoController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SleepController;
use App\Http\Controllers\WaterController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/privacy-policy', fn () => view('privacy-policy'))->name('privacy-policy');
Route::get('/terms-of-service', fn () => view('terms-of-service'))->name('terms-of-service');

Route::middleware('web')->group(function () {
    require __DIR__.'/auth.php';
});

require __DIR__.'/admin.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('/settings/language', [SettingsController::class, 'updateLanguage'])->name('settings.language');

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::get('/{user:username}', [ProfileController::class, 'show'])->name('profile.show');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    });

    Route::prefix('friends')->group(function () {
        Route::post('/{user:username}', [FriendController::class, 'store'])->name('friends.store');
        Route::post('/{user:username}/accept', [FriendController::class, 'accept'])->name('friends.accept');
        Route::delete('/{user:username}', [FriendController::class, 'remove'])->name('friends.remove');
    });

    Route::post('/follow/{user:username}', [FollowController::class, 'toggle'])->name('follow.toggle');
    Route::get('/profile/{user:username}/followers', [FollowController::class, 'followers'])->name('follow.followers');
    Route::get('/profile/{user:username}/following', [FollowController::class, 'following'])->name('follow.following');

    Route::prefix('tracker/foods')->group(function () {
        Route::get('/', [FoodController::class, 'index'])->name('foods.index');
        Route::post('/lookup', [FoodController::class, 'lookup'])->name('foods.lookup');
        Route::post('/calculate', [FoodController::class, 'calculate'])->name('foods.calculate');
        Route::get('/history', [FoodController::class, 'history'])->name('foods.history');
        Route::delete('/log/{mealLog}', [FoodController::class, 'destroy'])->name('foods.destroy');
    });

    Route::prefix('tracker/sleep')->group(function () {
        Route::get('/', [SleepController::class, 'index'])->name('sleep.index');
        Route::post('/', [SleepController::class, 'store'])->name('sleep.store');
    });

    Route::prefix('tracker/water')->group(function () {
        Route::get('/', [WaterController::class, 'index'])->name('water.index');
        Route::post('/', [WaterController::class, 'store'])->name('water.store');
    });

    Route::prefix('progress-photos')->group(function () {
        Route::get('/', [ProgressPhotoController::class, 'index'])->name('progress.index');
        Route::post('/', [ProgressPhotoController::class, 'store'])->name('progress.store');
        Route::patch('/{progress}', [ProgressPhotoController::class, 'update'])->name('progress.update');
        Route::delete('/{progress}', [ProgressPhotoController::class, 'destroy'])->name('progress.destroy');
    });

    Route::prefix('goals')->group(function () {
        Route::get('/', [GoalController::class, 'index'])->name('goals.index');
        Route::get('/create', [GoalController::class, 'create'])->name('goals.create');
        Route::post('/', [GoalController::class, 'store'])->name('goals.store');
        Route::get('/{goal}/edit', [GoalController::class, 'edit'])->name('goals.edit');
        Route::get('/{goal}/log', [GoalController::class, 'log'])->name('goals.log');
        Route::post('/{goal}/log', [GoalController::class, 'storeLog'])->name('goals.storeLog');
        Route::patch('/{goal}', [GoalController::class, 'update'])->name('goals.update');
        Route::delete('/{goal}', [GoalController::class, 'destroy'])->name('goals.destroy');
    });

    Route::prefix('calories')->group(function () {
        Route::get('/', [CalorieCalculatorController::class, 'index'])->name('calories.index');
        Route::post('/', [CalorieCalculatorController::class, 'calculate'])->name('calories.calculate');
    });

    Route::prefix('biography')->group(function () {
        Route::get('/', [BiographyController::class, 'edit'])->name('biography.edit');
        Route::post('/', [BiographyController::class, 'store'])->name('biography.store');
        Route::patch('/', [BiographyController::class, 'update'])->name('biography.update');
    });

    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/reaction', [PostController::class, 'toggleReaction'])->name('posts.toggleReaction');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');
    Route::post('/posts/{post}/views', [PostController::class, 'incrementViews'])->name('posts.views');
    Route::post('/posts/views/bulk', [PostController::class, 'bulkViews'])->name('posts.views.bulk');
    Route::post('/posts/stats/bulk', [PostController::class, 'bulkStats'])->name('posts.stats.bulk');
    Route::get('/posts/search-users', [PostController::class, 'searchUsers'])->name('posts.searchUsers');

    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::post('/comments/{comment}/toggle-reaction', [CommentController::class, 'toggleReaction'])->name('comments.toggle-reaction');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::prefix('calendar')->group(function () {
        Route::get('/', [CalendarController::class, 'index'])->name('activity-calendar');
        Route::post('/', [CalendarController::class, 'store'])->name('calendar.store');
        Route::patch('/{calendar}', [CalendarController::class, 'update'])->name('calendar.update');
        Route::delete('/{calendar}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
        Route::get('/events', [CalendarController::class, 'getEvents'])->name('calendar.events');
    });

    Route::get('/chats', [ConversationController::class, 'chats'])->name('chats.index');
    Route::get('/favorites', [ConversationController::class, 'favorites'])->name('favorites.index');

    Route::prefix('conversations')->group(function () {
        Route::get('/', [ConversationController::class, 'index'])->name('conversations.index');
        Route::post('/start/{user:username}', [ConversationController::class, 'start'])->name('conversations.start');
        Route::get('/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
        Route::post('/{conversation}', [ConversationController::class, 'send'])->name('conversations.send');
        Route::get('/{conversation}/poll', [ConversationController::class, 'poll'])->name('conversations.poll');
        Route::get('/{conversation}/history', [ConversationController::class, 'loadHistory'])->name('conversations.history');
        Route::get('/{conversation}/search', [ConversationController::class, 'search'])->name('conversations.search');
        Route::post('/{conversation}/typing', [ConversationController::class, 'typing'])->name('conversations.typing');
        Route::get('/{conversation}/typing-status', [ConversationController::class, 'typingStatus'])->name('conversations.typingStatus');
        Route::put('/{conversation}/messages/{message}', [ConversationController::class, 'editMessage'])->name('conversations.editMessage');
        Route::delete('/{conversation}/messages/{message}', [ConversationController::class, 'deleteMessage'])->name('conversations.deleteMessage');
        Route::post('/{conversation}/messages/{message}/react', [ConversationController::class, 'reactMessage'])->name('conversations.reactMessage');
        Route::post('/{conversation}/messages/{message}/forward', [ConversationController::class, 'forward'])->name('conversations.forward');
        Route::post('/{conversation}/messages/{message}/pin', [ConversationController::class, 'pinMessage'])->name('conversations.pinMessage');
        Route::post('/{conversation}/messages/{message}/favorite', [ConversationController::class, 'toggleFavorite'])->name('conversations.toggleFavorite');
        Route::post('/{conversation}/theme', [ConversationController::class, 'setTheme'])->name('conversations.setTheme');
    });

    Route::prefix('groups')->group(function () {
        Route::get('/', [GroupController::class, 'index'])->name('groups.index');
        Route::get('/create', [GroupController::class, 'create'])->name('groups.create');
        Route::post('/', [GroupController::class, 'store'])->name('groups.store');
        Route::get('/{group}', [GroupController::class, 'show'])->name('groups.show');
        Route::post('/{group}/send', [GroupController::class, 'send'])->name('groups.send');
        Route::get('/{group}/invite', [GroupController::class, 'invite'])->name('groups.invite');
        Route::post('/{group}/invite', [GroupController::class, 'sendInvite'])->name('groups.sendInvite');
        Route::post('/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
        Route::delete('/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
        Route::get('/{group}/poll', [GroupController::class, 'poll'])->name('groups.poll');
        Route::get('/{group}/history', [GroupController::class, 'loadHistory'])->name('groups.history');
        Route::get('/{group}/search', [GroupController::class, 'search'])->name('groups.search');
        Route::post('/{group}/typing', [GroupController::class, 'typing'])->name('groups.typing');
        Route::get('/{group}/typing-status', [GroupController::class, 'typingStatus'])->name('groups.typingStatus');
        Route::post('/{group}/avatar', [GroupController::class, 'updateAvatar'])->name('groups.avatar');
        Route::put('/{group}/name', [GroupController::class, 'updateName'])->name('groups.updateName');
        Route::put('/{group}/description', [GroupController::class, 'updateDescription'])->name('groups.updateDescription');
        Route::post('/{group}/members/{user:username}/role', [GroupController::class, 'setRole'])->name('groups.setRole');
        Route::delete('/{group}/members/{user:username}', [GroupController::class, 'removeMember'])->name('groups.removeMember');
        Route::put('/{group}/messages/{message}', [GroupController::class, 'editMessage'])->name('groups.editMessage');
        Route::delete('/{group}/messages/{message}', [GroupController::class, 'deleteMessage'])->name('groups.deleteMessage');
        Route::post('/{group}/messages/{message}/react', [GroupController::class, 'reactMessage'])->name('groups.reactMessage');
        Route::post('/{group}/messages/{message}/forward', [GroupController::class, 'forward'])->name('groups.forward');
        Route::post('/{group}/messages/{message}/pin', [GroupController::class, 'pinMessage'])->name('groups.pinMessage');
        Route::post('/{group}/messages/{message}/favorite', [GroupController::class, 'toggleFavorite'])->name('groups.toggleFavorite');
        Route::post('/{group}/theme', [GroupController::class, 'setTheme'])->name('groups.setTheme');
        Route::post('/{group}/polls', [GroupController::class, 'createPoll'])->name('groups.createPoll');
        Route::post('/{group}/polls/{poll}/vote', [GroupController::class, 'votePoll'])->name('groups.votePoll');
        Route::get('/{group}/members/search', [GroupController::class, 'searchMembers'])->name('groups.searchMembers');
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/invite/{invite}/accept', [NotificationController::class, 'acceptInvite'])->name('notifications.invite.accept');
        Route::post('/invite/{invite}/decline', [NotificationController::class, 'declineInvite'])->name('notifications.invite.decline');
    });
});
