<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Events\MessageSent;

class ChatController extends Controller
{

    public function customers()
    {
        $customers = User::where('id', '!=', Auth::id())->get();
        return view('chat.customer', compact('customers'));
    }
    

    public function chat(User $user)
    {
        $messages = Message::where(function ($q) use ($user) {
                $q->where('sender_id', Auth::id())
                  ->where('receiver_id', $user->id);
            })
            ->orWhere(function ($q) use ($user) {
                $q->where('sender_id', $user->id)
                  ->where('receiver_id', Auth::id());
            })
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return view('chat.private', compact('messages', 'user'));
    }

    // Send message
    public function sendMessage(Request $request, User $user)
    {
        $request->validate([
            'message' => 'nullable|string|max:500',
            'file_path' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xlsx,txt,zip|max:20480', // 20MB
        ]);

        
        $message = new Message();
        $message->sender_id = auth()->id();
        $message->receiver_id = $user->id;
        $message->message=$request->message;
        $path=null;
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $path = $file->store('uploads/chat_files', 'public');

        } 
        $message->file_path = $path;
        $message->save();
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
    // public function index()
    // {
    //     $messages = Message::with('sender')->latest()->take(20)->get()->reverse();
    //     // dd($messages);
    //     return view('chat.index', compact('messages'));
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'message' => 'required|string|max:500',
    //     ]);

    //     $message = Message::create([
    //         'sender_id' => Auth::id(),
    //         'receiver_id' => Auth::id(),
    //         'message' => $request->message,
    //     ]);

    //     return redirect()->route('chat')->with('success', 'Message sent!');
    // }
}