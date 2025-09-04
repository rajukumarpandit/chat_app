@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Chat Room</h5>
        </div>
        <div class="card-body" style="height: 400px; overflow-y: auto;">
            @foreach($messages as $msg)
                <div class="mb-2">
                    <strong>{{ $msg->sender->name }}:</strong>
                    <span>{{ $msg->message }}</span>
                    <small class="text-muted">({{ $msg->created_at->diffForHumans() }})</small>
                </div>
            @endforeach
        </div>
        <div class="card-footer">
            <form action="{{ route('chat.store') }}" method="POST">
                @csrf
                <div class="input-group">
                    <input type="text" name="message" class="form-control" placeholder="Type a message..." required>
                    <button class="btn btn-primary" type="submit">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
