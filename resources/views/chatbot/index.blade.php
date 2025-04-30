@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Chatbot</div>

                <div class="card-body">
                    <div id="chat-messages" class="chat-messages" style="height: 400px; overflow-y: auto; margin-bottom: 20px;">
                        <!-- Messages will be displayed here -->
                    </div>

                    <form id="chat-form" class="chat-form">
                        <div class="input-group">
                            <input type="text" id="message" class="form-control" placeholder="Type your message...">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.chat-messages {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 5px;
}

.message {
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 5px;
}

.user-message {
    background: #007bff;
    color: white;
    margin-left: 20%;
}

.bot-message {
    background: #e9ecef;
    margin-right: 20%;
}

.chat-form {
    margin-top: 20px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message');
    const chatMessages = document.getElementById('chat-messages');

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;

        // Add user message to chat
        addMessage(message, 'user');
        messageInput.value = '';

        // Send message to server
        fetch('/chatbot/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            // Add bot response to chat
            addMessage(data.response, 'bot');
        })
        .catch(error => {
            console.error('Error:', error);
            addMessage('Sorry, there was an error processing your request.', 'bot');
        });
    });

    function addMessage(message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}-message`;
        messageDiv.textContent = message;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});
</script>
@endsection 