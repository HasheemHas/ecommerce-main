<?php
/**
 * Frontend layout and controller logic for the H-Mart AI Chatbot.
 */
?>

<!-- Floating Action Button -->
<div class="chatbot-fab-container">
    <button class="chatbot-fab" id="chatbot-toggle-btn" title="Chat with H-Mart Assistant">
        <i class="fa fa-comments"></i>
    </button>

    <!-- Chat Panel Widget -->
    <div class="chatbot-panel" id="chatbot-widget-panel">
        <!-- Header -->
        <div class="chatbot-header">
            <div class="chatbot-header-info">
                <div class="chatbot-avatar">HM</div>
                <div class="chatbot-status-wrapper">
                    <h3 class="chatbot-title">H-Mart Assistant</h3>
                    <div class="chatbot-status">
                        <span class="chatbot-status-dot"></span> Online
                    </div>
                </div>
            </div>
            <button class="chatbot-close-btn" id="chatbot-close-btn" title="Close chat">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <!-- Scrollable Messages Body -->
        <div class="chatbot-body" id="chatbot-messages-body">
            <!-- Bot Welcome message -->
            <div class="chatbot-msg-row bot">
                <div class="chatbot-msg-bubble">
                    Hi<?php echo isset($_SESSION['CUSNAME']) ? ' ' . htmlspecialchars($_SESSION['CUSNAME']) : ''; ?>! 👋 I am your H-Mart shopping assistant. How can I help you today?
                </div>
            </div>
        </div>

        <!-- Quick suggestion actions -->
        <div class="chatbot-suggestions" id="chatbot-quick-pills">
            <div class="chatbot-pill" data-msg="Track my order">📦 Track Order</div>
            <div class="chatbot-pill" data-msg="Recommend some products">🔍 Browse Products</div>
            <div class="chatbot-pill" data-msg="What is your return policy?">💳 Return Policy</div>
            <div class="chatbot-pill" data-msg="How can I contact customer support?">📞 Contact Support</div>
        </div>

        <!-- Footer Form Input -->
        <div class="chatbot-footer">
            <form class="chatbot-input-form" id="chatbot-form" onsubmit="event.preventDefault(); sendChatMessage();">
                <input type="text" class="chatbot-input" id="chatbot-text-input" placeholder="Type a message..." autocomplete="off">
                <button type="submit" class="chatbot-send-btn" id="chatbot-send-btn">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatbotToggleBtn = document.getElementById('chatbot-toggle-btn');
    const chatbotCloseBtn = document.getElementById('chatbot-close-btn');
    const chatbotPanel = document.getElementById('chatbot-widget-panel');
    const chatbotBody = document.getElementById('chatbot-messages-body');
    const textInput = document.getElementById('chatbot-text-input');
    const sendBtn = document.getElementById('chatbot-send-btn');
    const pills = document.querySelectorAll('.chatbot-pill');
    let isTyping = false;

    // Toggle Chatbot widget visibility
    function toggleChatbot() {
        const isActive = chatbotPanel.classList.toggle('active');
        if (isActive) {
            textInput.focus();
            scrollToBottom();
            // Pulse animation disabled on toggle open
            chatbotToggleBtn.querySelector('i').className = 'fa fa-chevron-down';
        } else {
            chatbotToggleBtn.querySelector('i').className = 'fa fa-comments';
        }
    }

    chatbotToggleBtn.addEventListener('click', toggleChatbot);
    chatbotCloseBtn.addEventListener('click', toggleChatbot);

    // Scroll chat window to bottom
    function scrollToBottom() {
        chatbotBody.scrollTop = chatbotBody.scrollHeight;
    }

    // Parse links in markdown standard format like [text](url) to HTML links
    function parseMarkdownLinks(text) {
        const regex = /\[([^\]]+)\]\(([^)]+)\)/g;
        return text.replace(regex, '<a href="$2" target="_blank" style="color: #3b82f6; text-decoration: underline; font-weight: 600;">$1</a>');
    }

    // Add message bubble to chat body
    function addMessage(sender, text, products = []) {
        const row = document.createElement('div');
        row.className = `chatbot-msg-row ${sender}`;
        
        let messageHtml = `<div class="chatbot-msg-bubble">${parseMarkdownLinks(text)}`;
        
        // Render recommended products if present
        if (products && products.length > 0) {
            messageHtml += `<div class="chatbot-products-container">`;
            products.forEach(p => {
                messageHtml += `
                <a href="${p.url}" class="chatbot-product-card">
                    <img src="${p.image}" class="chatbot-product-img" alt="${p.name}">
                    <div class="chatbot-product-details">
                        <p class="chatbot-product-name">${p.name}</p>
                        <span class="chatbot-product-category">${p.category}</span>
                        <span class="chatbot-product-price">₹${parseFloat(p.price).toFixed(2)}</span>
                    </div>
                </a>`;
            });
            messageHtml += `</div>`;
        }
        
        messageHtml += `</div>`;
        row.innerHTML = messageHtml;
        chatbotBody.appendChild(row);
        scrollToBottom();
    }

    // Show/Hide typing loading indicators
    function showTypingIndicator() {
        if (document.getElementById('chatbot-typing-loader')) return;
        
        const row = document.createElement('div');
        row.className = 'chatbot-msg-row bot';
        row.id = 'chatbot-typing-loader';
        row.innerHTML = `
            <div class="chatbot-msg-bubble chatbot-typing-bubble">
                <span class="chatbot-typing-dot"></span>
                <span class="chatbot-typing-dot"></span>
                <span class="chatbot-typing-dot"></span>
            </div>`;
        chatbotBody.appendChild(row);
        scrollToBottom();
        isTyping = true;
        textInput.disabled = true;
        sendBtn.disabled = true;
    }

    function hideTypingIndicator() {
        const loader = document.getElementById('chatbot-typing-loader');
        if (loader) {
            loader.remove();
        }
        isTyping = false;
        textInput.disabled = false;
        sendBtn.disabled = false;
        textInput.focus();
    }

    // Handle sending a message
    window.sendChatMessage = function(customMsg = '') {
        const msgText = (customMsg || textInput.value).trim();
        if (!msgText || isTyping) return;

        // Clear input field
        if (!customMsg) textInput.value = '';

        // Add user message to screen
        addMessage('user', msgText);

        // Show bot thinking
        showTypingIndicator();

        // Query the chatbot backend API
        fetch('<?php echo str_replace('frontend/', 'backend/api/', web_root); ?>chatbot.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message: msgText })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network error');
            }
            return response.json();
        })
        .then(data => {
            hideTypingIndicator();
            if (data.status === 'success') {
                addMessage('bot', data.text, data.products || []);
            } else {
                addMessage('bot', 'Sorry, I encountered an issue processing your query. Please try again.');
            }
        })
        .catch(err => {
            hideTypingIndicator();
            addMessage('bot', 'Unable to connect to service. Please check your connection.');
            console.error('Chatbot error:', err);
        });
    };

    // Listen for quick action pill clicks
    pills.forEach(pill => {
        pill.addEventListener('click', function() {
            const query = this.getAttribute('data-msg');
            sendChatMessage(query);
        });
    });
});
</script>
