<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', function () {
    $path = public_path('index.html');

    if (File::exists($path)) {
        return Response::file($path);
    }

    abort(404);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// routes/web.php
Route::get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.dashboard')->middleware('auth');

Route::post('/login', function (Request $request) {
    if (Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false]);
});

Route::get('/api/user', function () {
    return Auth::user();
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');