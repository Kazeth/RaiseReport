<?php

use App\Http\Controllers\ThreadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

    Route::get('/', [ThreadController::class, 'index'])->name('home');

    Route::get('/search', [ThreadController::class, 'search'])->name('search');

    Route::get('/threads', [ThreadController::class, 'sortByDate'])->name('threads');

    Route::get('/search2', [ThreadController::class, 'searchSortByDate'])->name('searchDateSorted');

    Route::get('/thread/{id}', [ThreadController::class, 'show'])->name('detail');

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
    Route::get('/manageThread', function () {
        return view('manageThreadPage');
    })->name('manageThread');

    Route::get('/threadsRequests', function () {
        return view('threadsRequestsPage');
    })->name('threadsRequests');
});
