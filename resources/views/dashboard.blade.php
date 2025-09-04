@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 bg-light p-3" style="min-height: 100vh;">
                <h4 class="mb-4">Dashboard</h4>
                <ul class="list-group">
                    <li class="list-group-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="list-group-item"><a href="{{ route('chat') }}">Chat</a></li>
                    <li class="list-group-item"><a href="{{ route('chat.customers') }}">Customer</a></li>
                    <li class="list-group-item"><a href="{{ route('group.list') }}">Group list</a></li>
                    <li class="list-group-item"><a href="{{ route('create.group') }}">add group</a></li>
                    <li class="list-group-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 p-4">
                <h3>Welcome, {{ Auth::user()->name }}</h3>
                <p>This is your dashboard.</p>
            </div>
        </div>
    </div>
@endsection
