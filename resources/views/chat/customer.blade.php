@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Select a Customer to Chat</h3>
    <ul class="list-group">
        @foreach($customers as $customer)
            <li class="list-group-item">
                <a href="{{ route('chat.private', $customer->id) }}">
                    {{ $customer->name }} ({{ $customer->email }})
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
