<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use App\Models\GroupMessage;
use App\Events\GroupMessageSent;

class GroupChatController extends Controller {

    public function goupForm() {
        $users = User::where( 'id', '!=', auth()->id() )->get();
        return view( 'chat.creategroup', compact( 'users' ) );
    }

    public function store( Request $request ) {
        $request->validate( [
            'name' => 'required|string|max:255',
            'members' => 'required|array',
        ] );

        $group = Group::create( [
            'name' => $request->name,
            'created_by' => auth()->id(),
        ] );

        // Attach members + creator
        $members = array_unique( array_merge( $request->members, [ auth()->id() ] ) );

        $group->members()->attach( $members );
        return redirect()->route( 'dashboard' )->with( 'success', 'Group create successful!' );
        // return response()->json( [ 'success' => true, 'message' => 'Group created successfully!' ] );
    }

    public function index() {
        $groups = auth()->user()->groups;
        // pivot relation
        return view( 'groups.index', compact( 'groups' ) );
    }

    // Show group chat

    public function chat( Group $group ) {
        $messages = $group->messages()->with( 'sender' )->latest()->take( 50 )->get()->reverse();
        return view( 'groups.chat', compact( 'group', 'messages' ) );
    }

    // Send message

    public function sendMessage( Request $request, Group $group ) {
        $request->validate( [
            'message' => 'required|string',
            'file_path' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xlsx,txt,zip|max:20480',
        ] );
        // dd( $request->all() );
        // Create message
        $filePath = null;
        $fileType = null;

        if ( $request->hasFile( 'file_path' ) ) {
            $file = $request->file( 'file_path' );
            $filePath = $file->store( 'uploads/chat_files', 'public' );
        }
        $message = $group->messages()->create( [
            'sender_id' => auth()->id(),
            'message' => $request->message,
            'file_path' => $filePath,
        ] );

        // Broadcast event to others
        broadcast( new \App\Events\GroupMessageSent( $message ) )->toOthers();

        // Return JSON for AJAX
        return response()->json( [
            'success' => true,
            'message' => $message->load( 'sender' )
        ] );
    }

    // Fetch latest messages ( for AJAX refresh )

    public function fetchMessages( Group $group ) {
        $messages = $group->messages()->with( 'sender' )->latest()->take( 50 )->get()->reverse();
        return view( 'group.partials.messages', compact( 'messages' ) );
    }

}