<?php

use App\Http\Controllers\ThreadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

    // Landing Page
    Route::get('/', [ThreadController::class, 'index'])->name('home');
    Route::get('/showIndex', [ThreadController::class, 'showIndex'])->name('showIndex');

    // Threads Page
    Route::get('/threads', [ThreadController::class, 'sortByDate'])->name('threads');

    Route::get('/search2', [ThreadController::class, 'searchSortByDate'])->name('searchDateSorted');

    // Thread Detail Page
    Route::get('/thread/{id}', [ThreadController::class, 'show'])->name('detail');

    // Utility
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register_store'])->name('register.store');

    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login_process'])->name('login.process');
});

Route::middleware('user')->group(function () {

    Route::get('/thread/{id}/upvote', [ThreadController::class, 'upvote'])->name('upvote');
    Route::post('/thread/{id}/upvote', [ThreadController::class, 'upvote'])->name('upvote');

    Route::get('/userThreads', [ThreadController::class, 'userIndex'])->name('userThreads');

    Route::get('/createThread', function () {
        return view('createThreadPage');
    })->name('createThread');

    Route::get('/editThread', function () {
        return view('editThreadPage');
    })->name('editThread');

    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
});

Route::middleware('admin')->group(function () {
    Route::get('/admin/search', [ThreadController::class, 'searchAdmin'])->name('adminSearch');

    Route::get('/manageThread', [ThreadController::class, 'adminIndex'])->name('manageThread');

    Route::get('/admin/thread/{id}', [ThreadController::class, 'show'])->name('adminDetail');

    Route::post('/manageThread/{id}/approve', [ThreadController::class, 'approve'])->name('approve');

    Route::post('/manageThread/{id}/reject', [ThreadController::class, 'reject'])->name('reject');

    Route::post('/manageThread/{id}/revert', [ThreadController::class, 'revert'])->name('revert');

    Route::get('/showPending', [ThreadController::class, 'showPending'])->name('showPending');

    Route::get('/onHoldThreads', [ThreadController::class, 'showPending'])->name('onHoldThreads');
});
