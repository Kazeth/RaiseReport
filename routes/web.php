<?php

use App\Http\Controllers\ThreadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocaleController;

Route::get('/locale/{lang}', [LocaleController::class, 'setLocale'])->name('lang.switch');

// Landing Page
Route::get('/', [ThreadController::class, 'index'])->name('home');

// Threads Page
Route::get('/threads', [ThreadController::class, 'sortByDate'])->name('threads');

// Thread Detail Page
Route::get('/thread/{id}', [ThreadController::class, 'show'])->name('detail');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('guest')->group(function () {
    // Register Page
    // Regist
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register_store'])->name('register.store');

    // Login
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login_process'])->name('login.process');
});

// Delete Thread
    Route::delete('/thread/{id}', [ThreadController::class, 'destroy'])->name('thread.delete');

Route::middleware('user')->group(function () {
    // Upvote
    Route::post('/thread/{id}/upvote', [ThreadController::class, 'upvote'])->name('upvote');

    // User's Threads Page
    Route::get('/userThreads', [ThreadController::class, 'userIndex'])->name('userThreads');

    // Create Thread Page
    Route::get('/createThread', function () {
        return view('createThreadPage');
    })->name('createThread');
    Route::post('/createThread', [ThreadController::class, 'store'])->name('thread.store');

    // Edit Thread Page
    Route::get('/editThread/{id}', [ThreadController::class, 'edit'])->name('editThread');
    Route::post('/editThread/{id}', [ThreadController::class, 'update'])->name('thread.update');

    // Profile Page
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
});

Route::middleware('admin')->group(function () {
    // Manage Thread Page
    Route::get('/manageThread', [ThreadController::class, 'adminIndex'])->name('manageThread');
    // Approve
    Route::post('/manageThread/{id}/approve', [ThreadController::class, 'approve'])->name('approve');
    // Reject
    Route::post('/manageThread/{id}/reject', [ThreadController::class, 'reject'])->name('reject');
    // Revert
    Route::post('/manageThread/{id}/revert', [ThreadController::class, 'revert'])->name('revert');


    // On-Hold Threads Page
    Route::get('/onHoldThreads', [ThreadController::class, 'showPending'])->name('onHoldThreads');
});
