@foreach($messages as $msg)
    <div class="mb-2">
        <strong>{{ $msg->sender->name }}:</strong> {{ $msg->message }}
        <span class="text-muted small">{{ $msg->created_at->diffForHumans() }}</span>
    </div>
@endforeach
