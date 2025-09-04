@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body p-3" id="chatBox" style="height: 400px; overflow-y: auto; background-color: #f9f9f9;">

                <form id="createGroupForm" action="{{ route('group.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Group Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter group name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Members</label>
                        <select name="members[]" class="form-select" multiple required>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple</small>
                    </div>
                    <button class="btn btn-success">Create Group</button>
                </form>


            </div>


        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- <script>
    // Toastr Config
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": true,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    let lastMessageCount = {{ count($messages) }};

    // Auto-refresh chat every 3 seconds
    setInterval(function() {
        $.get("{{ route('chat.private', $user->id) }}", function(data) {
            let chatBox = $("#chatBox");
            let newContent = $(data).find("#chatBox").html();
            let newMessageCount = $(newContent).find(".mb-2").length;

            if (newMessageCount > lastMessageCount) {
                toastr.info("💬 New message received!");
                lastMessageCount = newMessageCount;
            }

            chatBox.html(newContent);
            chatBox.scrollTop(chatBox[0].scrollHeight);
        });
    }, 3000);

    // AJAX Send Message (no refresh)
    $("#chatForm").on("submit", function(e) {
        e.preventDefault();
        $.post($(this).attr("action"), $(this).serialize(), function() {
            $("#messageInput").val(""); // clear
            toastr.success("✅ Message sent!");
        });
    });
</script> --}}
@endpush
