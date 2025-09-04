<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* WhatsApp-like chat bubble design */
        .chat-bubble {
            padding: 8px 12px;
            border-radius: 15px;
            max-width: 60%;
            position: relative;
            font-size: 15px;
        }

        .chat-bubble.sent {
            background: #dcf8c6;
            /* light green */
            border-bottom-right-radius: 0;
        }

        .chat-bubble.received {
            background: #fff;
            border-bottom-left-radius: 0;
        }
    
    .bg-chat {
        background: #ece5dd; /* WhatsApp chat bg */
    }
    .chat-message {
        display: flex;
        margin-bottom: 12px;
    }
    .chat-left {
        justify-content: flex-start;
    }
    .chat-right {
        justify-content: flex-end;
    }
    .message-bubble {
        padding: 8px 12px;
        border-radius: 12px;
        max-width: 70%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        font-size: 14px;
        line-height: 1.4;
    }
    .chat-left .message-bubble {
        border-top-left-radius: 0;
    }
    .chat-right .message-bubble {
        border-top-right-radius: 0;
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
</head>

<body>
    @yield('content')
    @stack('script')
</body>

</html>
