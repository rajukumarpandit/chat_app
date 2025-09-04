@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Your Groups</h3>
    <ul class="list-group">
        {{-- {{$groups}} --}}
        @foreach($groups as $group)
            <li class="list-group-item">
                <a href="{{ route('groups.chat', $group->id) }}">{{ $group->name }}</a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
