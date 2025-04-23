<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;


Route::get('/', [ChatController::class, 'index'])->name('chat.index');
Route::post('/ask', [ChatController::class, 'ask'])->name('chat.ask');
Route::post('/new-chat', [ChatController::class, 'createConversation'])->name('chat.new');
// Route::get('/conversation/{id}', [ChatController::class, 'showConversation'])->name('chat.show');
Route::get('/chat/{id}', [ChatController::class, 'showConversation'])->name('chat.show');
Route::post('/clear-conversations', [ChatController::class, 'clearAllConversations'])->name('chat.clear');
