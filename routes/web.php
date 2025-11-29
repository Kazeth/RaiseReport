<?php

use App\Http\Controllers\ThreadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ThreadController::class, 'index'])->name('home');
Route::get('/search', [ThreadController::class, 'search'])->name('search');

Route::get('/threads', [ThreadController::class, 'sortByDate'])->name('threads');

Route::get('/search2', [ThreadController::class, 'searchSortByDate'])->name('searchDateSorted');

Route::get('/thread/{id}', [ThreadController::class, 'show'])->name('detail');

Route::get('/thread/{id}/upvote', [ThreadController::class, 'upvote'])->name('upvote');

Route::post('/thread/{id}/upvote', [ThreadController::class, 'upvote'])->name('upvote');



Route::get('/userThreads', function () {
    return view('userThreadsPage');
})->name('/userThreads');

Route::get('/createThread', function () {
    return view('createThreadPage');
})->name('/createThread');

Route::get('/editThread', function () {
    return view('editThreadPage');
})->name('/editThread');

Route::get('/profile', function () {
    return view('profilePage');
})->name('/profile');
