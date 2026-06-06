<?php
/**
 * Frontend layout and controller logic for the H-Mart AI Chatbot.
 */
?>
<style>
/* Premium H-Mart Chatbot CSS */

/* Floating Action Button (FAB) */
.chatbot-fab-container {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    font-family: 'Outfit', sans-serif;
}

.chatbot-fab {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border: none;
    cursor: pointer;
    box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3), 0 4px 6px -2px rgba(59, 130, 246, 0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    outline: none;
}

.chatbot-fab:hover {
    transform: scale(1.08) translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.4), 0 10px 10px -5px rgba(59, 130, 246, 0.1);
}

.chatbot-fab:active {
    transform: scale(0.95);
}

/* Pulsing effect around FAB */
.chatbot-fab::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    top: 0;
    left: 0;
    border: 2px solid #3b82f6;
    opacity: 0.6;
    animation: chatbot-pulse 2s infinite ease-in-out;
    pointer-events: none;
}

@keyframes chatbot-pulse {
    0% {
        transform: scale(1);
        opacity: 0.6;
    }
    100% {
        transform: scale(1.4);
        opacity: 0;
    }
}

/* Chat Widget Panel */
.chatbot-panel {
    position: fixed;
    bottom: 96px;
    right: 24px;
    width: 380px;
    max-width: calc(100vw - 48px);
    height: 520px;
    max-height: calc(100vh - 140px);
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(241, 245, 249, 0.8);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    z-index: 9999;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transform: translateY(20px) scale(0.95);
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    font-family: 'Outfit', sans-serif;
}

.chatbot-panel.active {
    transform: translateY(0) scale(1);
    opacity: 1;
    pointer-events: auto;
}

/* Chat Header */
.chatbot-header {
    padding: 16px 20px;
    background: linear-gradient(135deg, #1e3a8a, #3b82f6);
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
}

.chatbot-header-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.chatbot-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
    border: 2px solid rgba(255, 255, 255, 0.4);
}

.chatbot-status-wrapper {
    display: flex;
    flex-direction: column;
}

.chatbot-title {
    font-weight: 600;
    font-size: 15px;
    margin: 0;
    line-height: 1.2;
}

.chatbot-status {
    font-size: 11px;
    opacity: 0.85;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 2px;
}

.chatbot-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: #22c55e;
    display: inline-block;
}

.chatbot-close-btn {
    background: transparent;
    border: none;
    color: rgba(255, 255, 255, 0.8);
    font-size: 18px;
    cursor: pointer;
    padding: 4px;
    transition: color 0.2s;
    outline: none;
}

.chatbot-close-btn:hover {
    color: white;
}

/* Chat Body (Messages) */
.chatbot-body {
    flex: 1;
    padding: 16px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 12px;
    background: rgba(255, 255, 255, 0.3);
}

/* Custom Scrollbar for Chat Body */
.chatbot-body::-webkit-scrollbar {
    width: 6px;
}

.chatbot-body::-webkit-scrollbar-track {
    background: transparent;
}

.chatbot-body::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 3px;
}

.chatbot-body::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.2);
}

/* Message Bubbles */
.chatbot-msg-row {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    max-width: 85%;
    animation: chatbot-slide-in 0.25s ease-out forwards;
    opacity: 0;
    transform: translateY(10px);
}

@keyframes chatbot-slide-in {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chatbot-msg-row.bot {
    align-self: flex-start;
}

.chatbot-msg-row.user {
    align-self: flex-end;
    flex-direction: row-reverse;
}

.chatbot-msg-bubble {
    padding: 10px 14px;
    border-radius: 14px;
    font-size: 13.5px;
    line-height: 1.45;
    word-break: break-word;
}

.chatbot-msg-row.bot .chatbot-msg-bubble {
    background: white;
    color: #334155;
    border: 1px solid #e2e8f0;
    border-top-left-radius: 2px;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02);
}

.chatbot-msg-row.user .chatbot-msg-bubble {
    background: #3b82f6;
    color: white;
    border-top-right-radius: 2px;
    box-shadow: 0 1px 3px 0 rgba(59, 130, 246, 0.2);
}

.chatbot-msg-time {
    font-size: 10px;
    color: #94a3b8;
    margin-top: 4px;
    padding: 0 4px;
    align-self: flex-end;
}

.chatbot-msg-row.user .chatbot-msg-time {
    align-self: flex-start;
}

/* Structured Products Slider inside Chat */
.chatbot-products-container {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 6px;
    width: 100%;
}

.chatbot-product-card {
    display: flex;
    gap: 10px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 8px;
    align-items: center;
    transition: all 0.2s;
    text-decoration: none !important;
    color: inherit !important;
}

.chatbot-product-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    border-color: #cbd5e1;
}

.chatbot-product-img {
    width: 50px;
    height: 50px;
    border-radius: 6px;
    object-fit: cover;
    background: #f8fafc;
}

.chatbot-product-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.chatbot-product-name {
    font-size: 12px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.chatbot-product-category {
    font-size: 10px;
    color: #64748b;
    margin: 1px 0;
}

.chatbot-product-price {
    font-size: 12px;
    font-weight: 700;
    color: #3b82f6;
}

/* Horizontal Quick Suggestions Wrapper */
.chatbot-suggestions {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    border-top: 1px solid rgba(241, 245, 249, 0.8);
    background: rgba(255, 255, 255, 0.5);
    padding: 10px 14px;
}

.chatbot-suggestions::-webkit-scrollbar {
    height: 4px;
}

.chatbot-suggestions::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 2px;
}

.chatbot-pill {
    padding: 6px 12px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    font-size: 12px;
    color: #475569;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s;
    font-weight: 500;
}

.chatbot-pill:hover {
    border-color: #3b82f6;
    color: #3b82f6;
    background: rgba(59, 130, 246, 0.03);
}

/* Chat Footer (Input) */
.chatbot-footer {
    padding: 12px 16px;
    border-top: 1px solid rgba(241, 245, 249, 0.8);
    background: white;
}

.chatbot-input-form {
    display: flex;
    align-items: center;
    gap: 10px;
}

.chatbot-input {
    flex: 1;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 8px 16px;
    font-size: 13.5px;
    outline: none;
    transition: all 0.2s;
    color: #334155;
    background: #f8fafc;
}

.chatbot-input:focus {
    border-color: #3b82f6;
    background: white;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}

.chatbot-send-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #3b82f6;
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: all 0.2s;
    outline: none;
}

.chatbot-send-btn:hover {
    background: #1d4ed8;
    transform: scale(1.05);
}

.chatbot-send-btn:disabled {
    background: #cbd5e1;
    cursor: not-allowed;
    transform: none;
}

/* Typing Indicator Animation */
.chatbot-typing-bubble {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 12px 16px;
}

.chatbot-typing-dot {
    width: 6px;
    height: 6px;
    background: #64748b;
    border-radius: 50%;
    opacity: 0.4;
    animation: chatbot-bounce 1.4s infinite ease-in-out both;
}

.chatbot-typing-dot:nth-child(1) {
    animation-delay: -0.32s;
}

.chatbot-typing-dot:nth-child(2) {
    animation-delay: -0.16s;
}

@keyframes chatbot-bounce {
    0%, 80%, 100% { 
        transform: scale(0);
    } 
    40% { 
        transform: scale(1);
        opacity: 1;
    }
}

/* Premium Dark Mode Styling Overrides */
body.dark-mode .chatbot-panel {
    background: rgba(15, 23, 42, 0.9);
    border-color: rgba(51, 65, 85, 0.5);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
}

body.dark-mode .chatbot-body {
    background: rgba(15, 23, 42, 0.2);
}

body.dark-mode .chatbot-body::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
}

body.dark-mode .chatbot-body::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.2);
}

body.dark-mode .chatbot-msg-row.bot .chatbot-msg-bubble {
    background: #1e293b;
    color: #f1f5f9;
    border-color: #334155;
}

body.dark-mode .chatbot-product-card {
    background: #1e293b;
    border-color: #334155;
}

body.dark-mode .chatbot-product-card:hover {
    border-color: #475569;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
}

body.dark-mode .chatbot-product-name {
    color: #f1f5f9;
}

body.dark-mode .chatbot-product-category {
    color: #94a3b8;
}

body.dark-mode .chatbot-product-img {
    background: #0f172a;
}

body.dark-mode .chatbot-suggestions {
    border-color: rgba(51, 65, 85, 0.5);
    background: rgba(15, 23, 42, 0.4);
}

body.dark-mode .chatbot-pill {
    background: #1e293b;
    border-color: #334155;
    color: #cbd5e1;
}

body.dark-mode .chatbot-pill:hover {
    border-color: #3b82f6;
    color: #3b82f6;
    background: rgba(59, 130, 246, 0.05);
}

body.dark-mode .chatbot-footer {
    background: #0f172a;
    border-color: rgba(51, 65, 85, 0.5);
}

body.dark-mode .chatbot-input {
    background: #1e293b;
    border-color: #334155;
    color: #f1f5f9;
}

body.dark-mode .chatbot-input:focus {
    border-color: #3b82f6;
    background: #0f172a;
}

body.dark-mode .chatbot-typing-dot {
    background: #cbd5e1;
}

/* Responsive styles to prevent layout cut-off */
@media (max-width: 480px) {
    .chatbot-panel {
        right: 12px;
        bottom: 84px;
        width: calc(100vw - 24px);
        height: calc(100vh - 120px);
    }
    .chatbot-fab-container {
        bottom: 16px;
        right: 16px;
    }
}
</style>

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
function initChatbot() {
    const chatbotToggleBtn = document.getElementById('chatbot-toggle-btn');
    const chatbotCloseBtn = document.getElementById('chatbot-close-btn');
    const chatbotPanel = document.getElementById('chatbot-widget-panel');
    const chatbotBody = document.getElementById('chatbot-messages-body');
    const textInput = document.getElementById('chatbot-text-input');
    const sendBtn = document.getElementById('chatbot-send-btn');
    const pills = document.querySelectorAll('.chatbot-pill');
    let isTyping = false;

    if (!chatbotToggleBtn || !chatbotPanel) return;

    // Toggle Chatbot widget visibility
    function toggleChatbot() {
        const isActive = chatbotPanel.classList.toggle('active');
        const icon = chatbotToggleBtn.querySelector('i');
        if (isActive) {
            if (textInput) {
                textInput.disabled = false;
                textInput.focus();
            }
            scrollToBottom();
            if (icon) icon.className = 'fa fa-chevron-down';
        } else {
            if (icon) icon.className = 'fa fa-comments';
        }
    }

    chatbotToggleBtn.addEventListener('click', toggleChatbot);
    chatbotCloseBtn.addEventListener('click', toggleChatbot);

    // Scroll chat window to bottom
    function scrollToBottom() {
        if (chatbotBody) chatbotBody.scrollTop = chatbotBody.scrollHeight;
    }

    // Parse links in markdown standard format like [text](url) to HTML links
    function parseMarkdownLinks(text) {
        const regex = /\[([^\]]+)\]\(([^)]+)\)/g;
        return text.replace(regex, '<a href="$2" target="_blank" style="color: #3b82f6; text-decoration: underline; font-weight: 600;">$1</a>');
    }

    // Add message bubble to chat body
    function addMessage(sender, text, products = []) {
        if (!chatbotBody) return;
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
        if (!chatbotBody || document.getElementById('chatbot-typing-loader')) return;
        
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
        if (textInput) textInput.disabled = true;
        if (sendBtn) sendBtn.disabled = true;
    }

    function hideTypingIndicator() {
        const loader = document.getElementById('chatbot-typing-loader');
        if (loader) {
            loader.remove();
        }
        isTyping = false;
        if (textInput) textInput.disabled = false;
        if (sendBtn) sendBtn.disabled = false;
        if (textInput) textInput.focus();
    }

    // Handle sending a message
    window.sendChatMessage = function(customMsg = '') {
        const msgText = (customMsg || (textInput ? textInput.value : '')).trim();
        if (!msgText || isTyping) return;

        // Clear input field
        if (!customMsg && textInput) textInput.value = '';

        // Add user message to screen
        addMessage('user', msgText);

        // Show bot thinking
        showTypingIndicator();

        // Query the chatbot backend API
        fetch('<?php echo str_replace('frontend/', 'backend/api/', web_root); ?>chat.php', {
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
}

// Run immediately if DOM is already loaded, otherwise listen for DOMContentLoaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initChatbot);
} else {
    initChatbot();
}
</script>
