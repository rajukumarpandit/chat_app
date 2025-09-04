@extends('layouts.app')
@section('styles')
@endsection
@section('content')
    <div class="container py-4">
        <div class="card shadow border-0" style="height: 85vh;">
            <!-- Chat Header -->
            <div class="card-header bg-success text-white d-flex align-items-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" alt="{{ $user->name }}"
                    class="rounded-circle me-2" width="40" height="40">
                <h6 class="mb-0">{{ $user->name }}</h6>
            </div>

            <!-- Chat Messages -->
            <div class="card-body p-3" id="chatBox" style="overflow-y: auto; background: #ece5dd;">
                @foreach ($messages as $msg)
                    <div
                        class="d-flex mb-2 {{ $msg->sender_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="chat-bubble {{ $msg->sender_id == Auth::id() ? 'sent' : 'received' }}">
                            <div>{{ $msg->message }}</div>
                            <div class="small text-muted text-end mt-1" style="font-size: 11px;">
                                {{ $msg->created_at->format('H:i') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Chat Input -->
            <div class="card-footer bg-light p-2">
                <form id="chatForm" action="{{ route('chat.send', $user->id) }}" method="POST" class="d-flex">
                    @csrf
                    <input type="text" name="message" id="messageInput" class="form-control rounded-pill me-2"
                        placeholder="Type a message..." required>
                    <input type="file" name="file_path" id="fileInput" class="form-control mt-2">
                    <button class="btn btn-success rounded-circle">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection



@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        $(document).ready(function() {
            let chatBox = $("#chatBox");

            function scrollToBottom() {
                chatBox.scrollTop(chatBox[0].scrollHeight);
            }
            scrollToBottom();

            // ✅ Send message + file via AJAX
            // ✅ Send message + file via AJAX
            $("#chatForm").on("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr("action"),
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        let data = response.message; // backend se jo message aaya
                        let bubbleClass = 'sent';
                        let content = "";

                        // Agar text hai
                        if (data.message) {
                            content += `<div>${data.message}</div>`;
                        }

                        // Agar file hai
                        if (data.file_path) {
                            let fileUrl = `/storage/${data.file_path}`;

                            // Agar image
                            if (/\.(jpg|jpeg|png|gif)$/i.test(data.file_path)) {
                                content +=
                                    `<div><img src="${fileUrl}" width="200" class="rounded shadow mt-2"></div>`;
                            }
                            // Agar document
                            else {
                                content +=
                                    `<div><a href="${fileUrl}" target="_blank" class="text-primary">📄 Download File</a></div>`;
                            }
                        }

                        // ✅ Sender side bubble append
                        let newMsg = $(` 
                <div class="d-flex justify-content-end mb-2">
                    <div class="chat-bubble ${bubbleClass}">
                        ${content}
                        <div class="small text-muted text-end mt-1" style="font-size:11px;">
                            just now
                        </div>
                    </div>
                </div>
            `).hide().fadeIn(300);

                        $("#chatBox").append(newMsg);
                        scrollToBottom();

                        // Reset form inputs
                        $("#messageInput").val("");
                        $("#fileInput").val("");
                    }
                });
            });


            // ✅ Pusher config
            var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                forceTLS: true
            });

            // ✅ Private channel (user-specific)
            var channel = pusher.subscribe("private-chat.{{ auth()->id() }}");

            // ✅ Listen for new messages
            channel.bind("singlemessage", function(data) {
                console.log(data);
                let bubbleClass = data.sender_id == {{ auth()->id() }} ? 'sent' : 'received';
                let content = "";

                // Agar text message hai
                if (data.message) {
                    content += `<div>${data.message}</div>`;
                }

                // Agar file bheja gaya hai
                if (data.file_path) {
                    let fileUrl = `/storage/${data.file_path}`;

                    // Agar image hai
                    if (/\.(jpg|jpeg|png|gif)$/i.test(data.file_path)) {
                        content +=
                            `<div><img src="${fileUrl}" width="200" class="rounded shadow mt-2"></div>`;
                    }
                    // Agar document hai
                    else {
                        content +=
                            `<div><a href="${fileUrl}" target="_blank" class="text-primary">📄 Download File</a></div>`;
                    }
                }

                // ✅ Message bubble append
                let newMsg = $(` 
                <div class="d-flex ${data.sender_id == {{ auth()->id() }} ? 'justify-content-end' : 'justify-content-start'} mb-2">
                    <div class="chat-bubble ${bubbleClass}">
                        ${content}
                        <div class="small text-muted text-end mt-1" style="font-size:11px;">
                            ${new Date(data.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                        </div>
                    </div>
                </div>
            `).hide().fadeIn(300);

                $("#chatBox").append(newMsg);
                scrollToBottom();

                // ✅ Notification (sirf dusre user ke liye)
                if (data.sender_id != {{ auth()->id() }}) {
                    toastr.info("💬 New message from " + data.sender);
                }
            });

        });
    </script>

    {{-- <script>
        $(document).ready(function() {
            let chatBox = $("#chatBox");

            function scrollToBottom() {
                chatBox.scrollTop(chatBox[0].scrollHeight);
            }
            scrollToBottom();

            // ✅ Send message via AJAX
            $("#chatForm").on("submit", function(e) {
                e.preventDefault();
                let form = $(this);
                let message = $("#messageInput").val();

                $.post(form.attr("action"), form.serialize(), function(response) {
                    let newMsg = $(`
                <div class="d-flex justify-content-end mb-2">
                    <div class="chat-bubble sent">
                        <div>${message}</div>
                        <div class="small text-muted text-end mt-1" style="font-size:11px;">just now</div>
                    </div>
                </div>
            `);

                    $("#chatBox").append(newMsg);
                    scrollToBottom();
                    $("#messageInput").val("").focus();
                });
            });

            // ✅ Pusher config
            var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                forceTLS: true
            });

            var channel = pusher.subscribe("private-chat.{{ auth()->id() }}");

            channel.bind("singlemessage", function(data) {
                let bubbleClass = data.sender_id == {{ auth()->id() }} ? 'sent' : 'received';

                let newMsg = $(`
            <div class="d-flex ${data.sender_id == {{ auth()->id() }} ? 'justify-content-end' : 'justify-content-start'} mb-2">
                <div class="chat-bubble ${bubbleClass}">
                    <div>${data.message}</div>
                    <div class="small text-muted text-end mt-1" style="font-size:11px;">
                        ${new Date(data.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                    </div>
                </div>
            </div>
        `).hide().fadeIn(300);

                $("#chatBox").append(newMsg);
                scrollToBottom();

                if (data.sender_id != {{ auth()->id() }}) {
                    toastr.info("💬 New message from " + data.sender);
                }
            });

        });
    </script> --}}
@endpush
