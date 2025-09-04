@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow border-0" style="height: 85vh; border-radius: 10px;">
            <!-- Chat Header -->
            <div class="chat-header">
                <div class="chat-user">
                    <div class="avatar">
                        {{ strtoupper(substr($group->name, 0, 2)) }}
                    </div>
                    <span class="username">{{ $group->name }}</span>
                </div>
            </div>

            <!-- Chat Body -->
            <div class="chat-body" id="groupChatBox">
                @foreach ($messages as $msg)
                    <div class="message-wrapper {{ auth()->id() === $msg->sender_id ? 'sent' : 'received' }}">
                        <div class="message">
                            @if ($msg->file_path)
                                @if (Str::startsWith($msg->file_type, 'image/'))
                                    <img src="{{ asset('storage/' . $msg->file_path) }}"
                                        style="max-width:200px; border-radius:8px;">
                                @else
                                    <a href="{{ asset('storage/' . $msg->file_path) }}" target="_blank">📎 Download File</a>
                                @endif
                            @endif
                            @if ($msg->message)
                                <p>{{ $msg->message }}</p>
                            @endif
                            <span class="time">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Chat Footer -->
            <div class="chat-footer">
                <form id="groupChatForm" action="{{ route('group.chat.send', $group->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="message" id="groupMessageInput" placeholder="Type a message..." required>
                    <input type="file" name="file_path" id="fileInput" class="form-control mt-2">
                    <button type="submit" class="send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Container */
        .chat-container {
            width: 420px;
            height: 600px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            background: #fff;
            overflow: hidden;
        }

        /* Header */
        .chat-header {
            background: #147a46;
            color: white;
            padding: 12px;
            display: flex;
            align-items: center;
        }

        .chat-user {
            display: flex;
            align-items: center;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }

        .username {
            font-size: 16px;
            font-weight: 600;
        }

        /* Chat Body */
        .chat-body {
            flex: 1;
            padding: 15px;
            background: #ece5dd;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .message-wrapper {
            display: flex;
            margin-bottom: 8px;
        }

        .message {
            padding: 10px 14px;
            border-radius: 15px;
            font-size: 14px;
            max-width: 65%;
            position: relative;
            word-wrap: break-word;
        }

        .message p {
            margin: 0;
        }

        .time {
            font-size: 11px;
            margin-top: 4px;
            display: block;
            text-align: right;
            color: #555;
        }

        .received {
            justify-content: flex-start;
        }

        .received .message {
            background: #fff;
            border-radius: 10px 10px 10px 0;
        }

        .sent {
            justify-content: flex-end;
        }

        .sent .message {
            background: #dcf8c6;
            border-radius: 10px 10px 0 10px;
        }

        /* Footer */
        .chat-footer {
            padding: 10px;
            background: #fff;
            border-top: 1px solid #ddd;
        }

        .chat-footer form {
            display: flex;
            align-items: center;
        }

        .chat-footer input {
            flex: 1;
            padding: 10px;
            border-radius: 20px;
            border: 1px solid #ccc;
            outline: none;
        }

        .send-btn {
            background: #147a46;
            color: white;
            border: none;
            border-radius: 50%;
            width: 42px;
            height: 42px;
            margin-left: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        button {
            border: none;
            background: none;
            margin-left: 10px;
            cursor: pointer;
            font-size: 18px;
            color: #555;
            transition: all 0.2s ease-in-out;
        }
    </style>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    <script>
        $(document).ready(function() {
            let chatBox = $("#groupChatBox");
            chatBox.scrollTop(chatBox[0].scrollHeight);

            // Send message via AJAX
            // Send message via AJAX
            // Send message via AJAX
            $("#groupChatForm").on("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        if (res.success) {
                            let msg = res.message;

                            // Clear inputs
                            $("#groupMessageInput").val("");
                            $("#fileInput").val("");

                            // Build file preview
                            let fileHtml = "";
                            if (msg.file_path) {
                                if (msg.file_path.match(/\.(jpg|jpeg|png|gif)$/i)) {
                                    fileHtml = `<img src="/storage/${msg.file_path}" 
                                        style="max-width:200px; border-radius:8px;">`;
                                } else {
                                    fileHtml =
                                        `<a href="/storage/${msg.file_path}" target="_blank">📎 Download File</a>`;
                                }
                            }

                            // Append to chat
                            $("#groupChatBox").append(`
                    <div class="message-wrapper sent">
                        <div class="message" style="background:#dcf8c6; border-radius:10px 10px 0 10px">
                            ${msg.message ? `<p>${msg.message}</p>` : ""}
                            ${fileHtml}
                            <span class="time">${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                        </div>
                    </div>
                `);

                            // Scroll to bottom
                            $("#groupChatBox").scrollTop($("#groupChatBox")[0].scrollHeight);
                        }
                    }
                });
            });



            // Pusher setup
            var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
            });

            var channel = pusher.subscribe("group.{{ $group->id }}");
            channel.bind("GroupMessageSent", function(data) {
                let isOwnMessage = data.message.sender.id == {{ auth()->id() }};
                let wrapperClass = isOwnMessage ? 'sent' : 'received';
                let bgColor = isOwnMessage ? '#dcf8c6' : '#fff';
                let radius = isOwnMessage ? '10px 10px 0 10px' : '10px 10px 10px 0';
                let fileHtml = "";
                if (data.message.file_path) {
                    let fileUrl = `/storage/${data.message.file_path}`;
                    let fileName = data.message.file_path.split('/').pop();

                    if (/\.(jpg|jpeg|png|gif)$/i.test(fileName)) {
                        fileHtml = `<div><img src="${fileUrl}" width="200" class="rounded shadow">
                                    <div class="small text-muted">${fileName}</div></div>`;
                    } else if (/\.pdf$/i.test(fileName)) {
                        fileHtml = `<div><iframe src="${fileUrl}" width="300" height="200"></iframe>
                                    <a href="${fileUrl}" target="_blank">📄 Open PDF</a></div>`;
                    } else {
                        fileHtml = `<div><a href="${fileUrl}" target="_blank">📁 Download File</a>
                                    <div class="small text-muted">${fileName}</div></div>`;
                    }
                }
                chatBox.append(`
                            <div class="message-wrapper ${wrapperClass}">
                        <div class="message" style="background:${bgColor}; border-radius:${radius}">
                            ${fileHtml}
                            ${data.message.message ? `<p>${data.message.message}</p>` : ""}
                            <span class="time">${new Date(data.message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                        </div>
                    </div>
                        `);

                chatBox.scrollTop(chatBox[0].scrollHeight);

                if (!isOwnMessage) {
                    toastr.info("💬 New message from " + data.message.sender.name);
                }
            });
        });
    </script>
@endpush
