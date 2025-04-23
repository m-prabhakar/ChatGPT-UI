<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class ChatController extends Controller
{
    public function index()
    {
        $conversation = Conversation::create([
            'title' => 'New Conversation'
        ]);
    
        session(['conversation_id' => $conversation->id]);
    
        $conversations = Conversation::latest()->get(); // or user-specific if you have auth
    
        return view('chat.index', [
            'conversations' => $conversations
        ]);
    }

    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'conversation_id' => 'required|exists:conversations,id',
        ]);
    
        $conversation = Conversation::findOrFail($request->conversation_id);
    
        // Save user's message
        Message::create([
            'conversation_id' => $conversation->id,
            'sender' => 'user',
            'message' => $request->message,
        ]);
    
        // Generate assistant reply (replace this with real ChatGPT API logic later)
        $reply = "Hello! This is a test response.";
    
        // Save assistant's reply
        Message::create([
            'conversation_id' => $conversation->id,
            'sender' => 'assistant',
            'message' => $reply,
        ]);
    
        return response()->json(['reply' => $reply]);
    }

    public function createConversation($id)
    {
        $conversation = Conversation::with('messages')->findOrFail($id);
        session(['conversation_id' => $conversation->id]);
    
        $conversations = Conversation::all(); // For sidebar list
    
        return view('chat.index', compact('conversation', 'conversations'));
    }
    
    

    public function clearAllConversations()
    {
        // Disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate child table first
        Message::truncate();
        Conversation::truncate();

        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->route('chat.index');
    }
    
}
