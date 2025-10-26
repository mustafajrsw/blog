<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostStatusController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\ReactionTypeController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
|
*/

// 🧱 POSTS
Route::prefix('posts')->name('posts.')->controller(PostController::class)->group(function () {
    Route::get('deleted', 'deleted')->name('deleted');
    Route::get('restore/{id}', 'restore')->name('restore');
    Route::delete('force-delete/{id}', 'forceDelete')->name('forceDelete');
});
Route::resource('posts', PostController::class);

// 💬 COMMENTS
Route::prefix('comments')->name('comments.')->controller(CommentController::class)->group(function () {
    Route::get('deleted', 'deleted')->name('deleted');
    Route::get('restore/{id}', 'restore')->name('restore');
    Route::delete('force-delete/{id}', 'forceDelete')->name('forceDelete');
});
Route::resource('comments', CommentController::class);

// 💭 REPLIES
Route::prefix('replies')->name('replies.')->controller(ReplyController::class)->group(function () {
    Route::get('deleted', 'deleted')->name('deleted');
    Route::get('restore/{id}', 'restore')->name('restore');
    Route::delete('force-delete/{id}', 'forceDelete')->name('forceDelete');
});
Route::resource('replies', ReplyController::class);

// ❤️ REACTIONS
Route::prefix('reactions')->name('reactions.')->controller(ReactionController::class)->group(function () {
    Route::get('deleted', 'deleted')->name('deleted');
    Route::get('restore/{id}', 'restore')->name('restore');
    Route::delete('force-delete/{id}', 'forceDelete')->name('forceDelete');
});
Route::resource('reactions', ReactionController::class);

// 📊 POST STATUSES
Route::prefix('post-statuses')->name('post_statuses.')->controller(PostStatusController::class)->group(function () {
    Route::get('deleted', 'deleted')->name('deleted');
    Route::get('restore/{id}', 'restore')->name('restore');
    Route::delete('force-delete/{id}', 'forceDelete')->name('forceDelete');
});
Route::resource('post-statuses', PostStatusController::class);

// 😊 REACTION TYPES
Route::prefix('reaction-types')->name('reaction_types.')->controller(ReactionTypeController::class)->group(function () {
    Route::get('deleted', 'deleted')->name('deleted');
    Route::get('restore/{id}', 'restore')->name('restore');
    Route::delete('force-delete/{id}', 'forceDelete')->name('forceDelete');
});
Route::resource('reaction-types', ReactionTypeController::class);

// 👤 USERS
Route::prefix('users')->name('users.')->controller(UserController::class)->group(function () {
    Route::get('deleted', 'deleted')->name('deleted');
    Route::get('restore/{id}', 'restore')->name('restore');
    Route::delete('force-delete/{id}', 'forceDelete')->name('forceDelete');

    // ✅ Changed to PATCH (not GET) for proper HTTP verb use
    Route::patch('{id}/activate', 'activate')->name('activate');
    Route::patch('{id}/deactivate', 'deactivate')->name('deactivate');
});
Route::resource('users', UserController::class);

// 🪪 PROFILES
Route::prefix('profiles')->name('profiles.')->controller(ProfileController::class)->group(function () {
    Route::get('deleted', 'deleted')->name('deleted');
    Route::get('restore/{id}', 'restore')->name('restore');
    Route::delete('force-delete/{id}', 'forceDelete')->name('forceDelete');
});
Route::resource('profiles', ProfileController::class);
