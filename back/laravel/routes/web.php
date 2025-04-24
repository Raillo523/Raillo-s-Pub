<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    $path = public_path('index.html');

    if (File::exists($path)) {
        return Response::file($path);
    }

    abort(404);
});

// Obtener el CSRF token para AJAX
Route::get('/get-csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// routes/web.php
Route::get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.dashboard')->middleware('auth');

// Ruta para hacer login
Route::post('/login-user', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['success' => false, 'message' => 'Credenciales inválidas']);
    }

    Session::put('user', $user->name); // Guardamos el nombre en sesión

    return response()->json(['success' => true, 'user' => $user->name]);
});

// Ruta para verificar si hay sesión activa
Route::get('/check-session', function () {
    if (Session::has('user')) {
        return response()->json(['logged_in' => true, 'user' => Session::get('user')]);
    }

    return response()->json(['logged_in' => false]);
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