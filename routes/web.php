<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landingPage');
})->name('/');

Route::get('/threads', function () {
    return view('threadsPage');
})->name('/threads');

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
