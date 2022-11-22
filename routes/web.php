<?php

use Illuminate\Support\Facades\Route;

Route::get('/index', function () {
    return view('admin/index');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/book', function () {
   return view('admin/trial/book');
});

Route::get('/book1', function () {
    return view('admin/trial/book1');
});

Route::get('/book2', function () {
    return view('admin/trial/book2');
});


Route::resource('student', \App\Http\Controllers\Admin\StudentController::class);

Route::resource('teacher',\App\Http\Controllers\Admin\TeacherController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('photo', \App\Http\Controllers\Admin\PhotoController::class);

Route::resource('user', \App\Http\Controllers\Admin\UserController::class);
