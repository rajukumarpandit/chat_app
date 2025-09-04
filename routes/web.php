<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\GroupChatController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', [AuthController::class, 'showForm'])->name('login');

// Register & Login
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected route (dashboard)

Broadcast::routes(['middleware' => ['auth']]);
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');


    Route::get('/customers', [ChatController::class, 'customers'])->name('chat.customers');
    Route::get('/chat/{user}', [ChatController::class, 'chat'])->name('chat.private');
    Route::post('/chat/{user}', [ChatController::class, 'sendMessage'])->name('chat.send');

    //group chat
    Route::get('/groups/create', [GroupChatController::class, 'goupForm'])->name('create.group');
    Route::get('/groups', [GroupChatController::class, 'index'])->name('group.list');
    Route::post('/group/store', [GroupChatController::class, 'store'])->name('group.store');
    Route::get('/group/{group}/chat', [GroupChatController::class, 'chat'])->name('groups.chat');
    Route::post('/group/{group}/send', [GroupChatController::class, 'sendMessage'])->name('group.chat.send');
    // Route::get('/group/{group}/fetch', [GroupChatController::class, 'fetchMessages'])->name('group.chat.fetch');
});