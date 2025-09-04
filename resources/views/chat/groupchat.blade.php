{{-- @extends('layouts.app')

@section('content')
    <div class="container">
        <h4 class="mb-3">Group Chat: {{ $group->name }}</h4>
        <div class="card shadow-lg border-0 rounded-3">

            <div class="card-body" id="groupChatBox" style="height: 400px; overflow-y: auto;">
                @foreach ($messages as $msg)
                    <div class="mb-2">
                        <strong>{{ $msg->sender->name }}:</strong> {{ $msg->message }}
                        <span class="text-muted small">{{ $msg->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
            <div class="card-footer">
                <form id="groupChatForm" action="{{ route('group.chat.send', $group->id) }}" method="POST"
                    class="p-2 border-top">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="message" id="groupMessageInput" class="form-control"
                            placeholder="Type message..." required>
                        <button class="btn btn-primary">
                            <i class="bi bi-send"></i> Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        let lastGroupMessageCount = {{ count($messages) }};

        // Auto-refresh every 3s
        setInterval(function() {
            $.get("{{ route('groups.chat', $group->id) }}", function(data) {
                let chatBox = $("#groupChatBox");
                let newContent = $(data).find("#groupChatBox").html();
                let newMessageCount = $(newContent).find(".mb-2").length;

                if (newMessageCount > lastGroupMessageCount) {
                    toastr.info("💬 New group message!");
                    lastGroupMessageCount = newMessageCount;
                }

                chatBox.html(newContent);
                chatBox.scrollTop(chatBox[0].scrollHeight);
            });
        }, 3000);

        // Send message via AJAX
        $("#groupChatForm").on("submit", function(e) {
            e.preventDefault();
            $.post($(this).attr("action"), $(this).serialize(), function() {
                $("#groupMessageInput").val("");
                toastr.success("✅ Message sent to group!");
            });
        });

        
    </script>
@endpush --}}
