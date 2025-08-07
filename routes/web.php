<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Profile Routes
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/skills', [ProfileController::class, 'updateSkills'])->name('profile.skills.update');
    Route::put('/profile/project', [ProfileController::class, 'updateProject'])->name('profile.project.update');
    Route::put('/profile/project/archive', [ProfileController::class, 'archiveProject'])->name('profile.project.archive');
    
    // Posts Routes
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    
    // Invitation Routes
    Route::post('/invitations/send', [App\Http\Controllers\InvitationController::class, 'send'])->name('invitations.send');
    Route::post('/invitations/{invitation}/accept', [App\Http\Controllers\InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/invitations/{invitation}/decline', [App\Http\Controllers\InvitationController::class, 'decline'])->name('invitations.decline');
    Route::get('/invitations', [App\Http\Controllers\InvitationController::class, 'index'])->name('invitations.index');
});
