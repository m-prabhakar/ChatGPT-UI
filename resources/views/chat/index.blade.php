<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ChatGPT UI Clone</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen">
<div class="flex h-full">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col">
        <form action="{{ route('chat.new') }}" method="POST" class="p-4">
            @csrf
            <button class="bg-green-600 px-4 py-2 rounded w-full">+ New Chat</button>
        </form>
        <form action="{{ route('chat.clear') }}" method="POST" class="p-4">
            @csrf
            <button class="bg-red-600 px-4 py-2 rounded w-full">Clear All Conversations</button>
        </form>
        <div id="chat-window" class="flex-1 p-6 overflow-y-auto">
            @if (isset($conversation))
                @foreach ($conversation->messages as $message)
                    <div class="p-2 mb-2 {{ $message->sender === 'assistant' ? 'bg-gray-200' : '' }} rounded">
                        <strong>{{ ucfirst($message->sender) }}:</strong> {{ $message->message }}
                    </div>
                @endforeach
            @endif
        </div>

        <div class="flex-1 overflow-y-auto px-4">
            <ul>
                @foreach ($conversations as $conv)
                    <li class="py-2 px-2 hover:bg-gray-800 cursor-pointer">
                        <a href="{{ route('chat.show', $conv->id) }}">{{ $conv->title }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </aside>

    <!-- Chat UI -->
    <main class="flex-1 flex flex-col bg-gray-100">
        <div id="chat-window" class="flex-1 p-6 overflow-y-auto"></div>
        <div class="p-4 border-t bg-white">
            <form id="chat-form">
                @csrf
                <input type="hidden" name="conversation_id" id="conversation_id" value="{{ session('conversation_id') }}">
                <div class="flex">
                    <input type="text" id="message" placeholder="Ask something..." class="flex-1 border rounded px-4 py-2">
                    <button type="submit" class="ml-2 bg-blue-600 text-white px-4 py-2 rounded">Send</button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
    // CSRF Token for Axios
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const messageInput = document.getElementById('message');
        const message = messageInput.value.trim();
        const conversationId = document.getElementById('conversation_id').value;
        const chatWindow = document.getElementById('chat-window');

        if (!message) return;

        // Append user message
        chatWindow.innerHTML += `<div class='p-2 mb-2'><strong>You:</strong> ${message}</div>`;
        messageInput.value = '';

        console.log("Message:", message);
        console.log("Conversation ID:", conversationId);

        // Send to backend
        axios.post('/ask', {
            message: message,
            conversation_id: conversationId
        })
        .then(res => {
            chatWindow.innerHTML += `<div class='p-2 mb-2 bg-gray-200 rounded'><strong>Assistant:</strong> ${res.data.reply}</div>`;
            chatWindow.scrollTop = chatWindow.scrollHeight;
        })
        .catch(err => {
            console.error('Error:', err);
            chatWindow.innerHTML += `<div class='p-2 mb-2 text-red-600'><strong>Error:</strong> Could not get reply.</div>`;
        });
    });
</script>
</body>
</html>
