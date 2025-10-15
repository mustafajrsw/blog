<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostStatusController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\ReactionTypeController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Welcome  API';
});

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login/{platform}', 'login')
        ->whereIn('platform', ['web', 'mobile']);
    Route::post('password/forgot', 'forgot_password');
    Route::post('password/reset', 'reset_password');
});

Route::prefix('auth')->controller(AuthController::class)->middleware(['auth:sanctum', 'isActive', 'verifiedEmail'])->group(function () {

    // Email Verification
    Route::prefix('email')->group(function () {
        Route::get('verify/{token}', 'verify_email');
        Route::get('re-verify', 're_verify_email');
    });

    // All Sessions
    Route::prefix('sessions')->group(function () {
        Route::get('active', 'active_sessions')->middleware('tokenType:web');
        Route::get('current', 'current_session');
        Route::get('others', 'other_sessions');
        Route::get('{id}', 'show_session')->middleware('hasRoles:admin,manager');
    });

    // logout
    Route::prefix('logout')->group(function () {
        Route::post('all', 'logout_all')->middleware('tokenType:web');
        Route::post('current', 'logout_current');
        Route::post('others', 'logout_others');
        Route::get('{id}', 'logout_session')->middleware('hasRoles:admin,manager');
    });

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('', 'show_profile');
        Route::put('', 'update_profile');
        Route::post('change-photo', 'change_photo');
    });

    // Change password
    Route::put('change-password', 'change_password');
});

Route::middleware(['auth:sanctum', 'isActive', 'verifiedEmail'])->group(function () {

    // Posts
    Route::prefix('posts')->controller(PostController::class)->group(function () {
        Route::get('deleted', 'deleted')->middleware(['hasRoles:admin,manager']);
        Route::get('restore/{id}', 'restore')->middleware(['hasRoles:admin']);
        Route::delete('force-delete/{id}', 'force_delete')->middleware(['hasRoles:admin']);
    });

    // Comments
    Route::prefix('comments')->controller(CommentController::class)->group(function () {
        Route::get('deleted', 'deleted')->middleware('hasRoles:admin,manager');
        Route::get('restore/{id}', 'restore')->middleware('hasRoles:admin');
        Route::delete('force-delete/{id}', 'force_delete')->middleware('hasRoles:admin');
    });

    // Replies
    Route::prefix('replies')->controller(ReplyController::class)->group(function () {
        Route::get('deleted', 'deleted')->middleware('hasRoles:admin,manager');
        Route::get('restore/{id}', 'restore')->middleware('hasRoles:admin');
        Route::delete('force-delete/{id}', 'force_delete')->middleware('hasRoles:admin');
    });

    // Reactions
    Route::prefix('reactions')->controller(ReactionController::class)->group(function () {
        Route::get('deleted', 'deleted')->middleware('hasRoles:admin,manager');
        Route::get('restore/{id}', 'restore')->middleware('hasRoles:admin');
        Route::delete('force-delete/{id}', 'force_delete')->middleware('hasRoles:admin');
    });

    // Post-Statuses
    Route::prefix('post-statuses')->controller(PostStatusController::class)->group(function () {
        Route::get('deleted', 'deleted')->middleware('hasRoles:admin,manager');
        Route::get('restore/{id}', 'restore')->middleware('hasRoles:admin');
        Route::delete('force-delete/{id}', 'force_delete')->middleware('hasRoles:admin');
    });

    // Reaction-Types
    Route::prefix('reaction-types')->controller(ReactionTypeController::class)->group(function () {
        Route::get('deleted', 'deleted')->middleware('hasRoles:admin,manager');
        Route::get('restore/{id}', 'restore')->middleware('hasRoles:admin');
        Route::delete('force-delete/{id}', 'force_delete')->middleware('hasRoles:admin');
    });

    // Users
    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::get('deleted', 'deleted')->middleware('hasRoles:admin');
        Route::get('restore/{id}', 'restore')->middleware('hasRoles:admin');
        Route::delete('force-delete/{id}', 'force_delete')->middleware(['hasRoles:admin']);
        Route::get('activate/{id}', 'activate')->middleware('hasRoles:admin,manager');
        Route::get('deactivate/{id}', 'deactivate')->middleware('hasRoles:admin,manager');
    });

    // Dashboard
    // Route::prefix('dashboard')->controller(DashboardController::class)->group(function () {
    //     Route::get('deleted', 'deleted');
    //     Route::get('restore/{id}', 'restore');
    //     Route::delete('force-delete/{id}', 'force_delete');
    // });

    // Posts, comments, reactions, users, replies
    Route::apiResources(
        [
            'post-statuses' => PostStatusController::class,
            'posts' => PostController::class,
            'comments' => CommentController::class,
            'reaction-types' => ReactionTypeController::class,
            'replies' => ReplyController::class,
            'users' => UserController::class,
            'reactions' => ReactionController::class,
        ]
    );

});
