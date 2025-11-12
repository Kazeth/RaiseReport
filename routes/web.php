<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landingPage');
});

Route::get('/threads', function () {
    return view('threadsPage');
});

Route::get('/userThreads', function () {
    return view('userThreadsPage');
});

Route::get('/createThread', function () {
    return view('createThreadPage');
});

Route::get('/editThread', function () {
    return view('editThreadPage');
});

Route::get('/profile', function () {
    return view('profilePage');
});
