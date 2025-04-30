<div id="chat-button" class="chat-button">
    <i class="fas fa-robot"></i>
</div>

<style>
.chat-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 60px;
    height: 60px;
    background-color: #007bff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    z-index: 1000;
}

.chat-button:hover {
    transform: scale(1.1);
    background-color: #0056b3;
}

.chat-button i {
    color: white;
    font-size: 24px;
}

#chat-window {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 350px;
    height: 500px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    display: none;
    z-index: 1000;
    overflow: hidden;
}

#chat-window.active {
    display: block;
}

.chat-header {
    background: #007bff;
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-header h3 {
    margin: 0;
    font-size: 18px;
}

.close-chat {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 20px;
}

.chat-messages {
    height: 380px;
    overflow-y: auto;
    padding: 15px;
}

.message {
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 5px;
    max-width: 80%;
}

.user-message {
    background: #007bff;
    color: white;
    margin-left: auto;
}

.bot-message {
    background: #e9ecef;
    margin-right: auto;
}

.suggested-messages {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 10px;
}

.suggested-message {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    padding: 8px 15px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
}

.suggested-message:hover {
    background: #e9ecef;
    transform: translateY(-1px);
}

.chat-input {
    padding: 15px;
    border-top: 1px solid #eee;
    display: flex;
    gap: 10px;
}

.chat-input input {
    flex: 1;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.chat-input button {
    padding: 8px 15px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.chat-input button:hover {
    background: #0056b3;
}

.typing-indicator {
    display: none;
    margin-bottom: 10px;
    color: #666;
    font-style: italic;
}

.typing-indicator.active {
    display: block;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatButton = document.getElementById('chat-button');
    const chatWindow = document.createElement('div');
    chatWindow.id = 'chat-window';
    
    // Get CSRF token
    const csrfMetaTag = document.querySelector('meta[name="csrf-token"]');
    const token = csrfMetaTag ? csrfMetaTag.getAttribute('content') : '';
    
    if (!token) {
        console.warn('CSRF token not found. Chat functionality may be limited.');
    }
    
    // Suggested messages for movie recommendations
    const suggestedMessages = [
        "Which movie is best to watch with family?",
        "What are the top horror movies to watch tonight?",
        "Can you recommend some action movies?",
        "What are the best romantic comedies?",
        "Which movies are trending right now?",
        "What are some good movies for date night?",
        "Can you suggest some classic movies?",
        "What are the best movies of this year?"
    ];
    
    // Create chat window HTML
    chatWindow.innerHTML = `
        <div class="chat-header">
            <h3>Movie Recommendations</h3>
            <button class="close-chat">&times;</button>
        </div>
        <div class="chat-messages">
            <div class="message bot-message">
                Hi! I'm your movie recommendation assistant. How can I help you today?
            </div>
            <div class="suggested-messages">
                ${suggestedMessages.map(msg => `
                    <div class="suggested-message">${msg}</div>
                `).join('')}
            </div>
        </div>
        <div class="chat-input">
            <input type="text" placeholder="Type your message...">
            <button type="button">Send</button>
        </div>
    `;
    
    document.body.appendChild(chatWindow);
    
    const closeButton = chatWindow.querySelector('.close-chat');
    const input = chatWindow.querySelector('input');
    const sendButton = chatWindow.querySelector('button[type="button"]');
    const messagesContainer = chatWindow.querySelector('.chat-messages');
    const suggestedMessageButtons = chatWindow.querySelectorAll('.suggested-message');
    
    // Toggle chat window
    chatButton.addEventListener('click', () => {
        chatWindow.classList.toggle('active');
    });
    
    // Close chat window
    closeButton.addEventListener('click', () => {
        chatWindow.classList.remove('active');
    });
    
    // Handle suggested message clicks
    suggestedMessageButtons.forEach(button => {
        button.addEventListener('click', () => {
            const message = button.textContent;
            input.value = message;
            sendMessage();
        });
    });
    
    // Send message
    function sendMessage() {
        const message = input.value.trim();
        if (!message) return;
        
        // Add user message
        addMessage(message, 'user');
        input.value = '';
        
        // Add typing indicator
        const typingIndicator = document.createElement('div');
        typingIndicator.className = 'typing-indicator active';
        typingIndicator.textContent = 'AI is typing...';
        messagesContainer.appendChild(typingIndicator);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        
        // Prepare headers
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
        
        // Add CSRF token if available
        if (token) {
            headers['X-CSRF-TOKEN'] = token;
        }
        
        // Send to server
        fetch('/chatbot/send', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({ message: message })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Remove typing indicator
            typingIndicator.remove();
            // Add bot response
            addMessage(data.response, 'bot');
        })
        .catch(error => {
            console.error('Error:', error);
            // Remove typing indicator
            typingIndicator.remove();
            addMessage('Sorry, there was an error processing your request.', 'bot');
        });
    }
    
    // Add message to chat
    function addMessage(message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}-message`;
        messageDiv.textContent = message;
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    
    // Send message on button click
    sendButton.addEventListener('click', sendMessage);
    
    // Send message on Enter key
    input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
});
</script> 