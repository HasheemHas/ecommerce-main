<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    .realtime-wrapper {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
        padding: 40px 0 80px;
        min-height: calc(100vh - 100px);
    }
    .chat-container {
        max-width: 900px;
        margin: 0 auto;
        background: white;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 700px;
        border: 1px solid #e2e8f0;
    }
    
    /* Header */
    .chat-header {
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        padding: 25px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: white;
    }
    .chat-header-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .agent-avatar {
        position: relative;
    }
    .agent-avatar img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.3);
        object-fit: cover;
    }
    .status-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        background: #10b981;
        border-radius: 50%;
        border: 2px solid #1e3a8a;
    }
    .agent-details h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .agent-details p {
        margin: 2px 0 0 0;
        font-size: 13px;
        color: #bfdbfe;
        font-weight: 500;
    }
    .chat-header-actions button {
        background: rgba(255,255,255,0.1);
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        margin-left: 8px;
    }
    .chat-header-actions button:hover {
        background: rgba(255,255,255,0.2);
    }

    /* Chat Body */
    .chat-body {
        flex: 1;
        background: #f8fafc;
        padding: 30px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .chat-date-divider {
        text-align: center;
        margin: 10px 0;
        position: relative;
    }
    .chat-date-divider span {
        background: #f8fafc;
        padding: 0 15px;
        font-size: 12px;
        font-weight: 600;
        color: #94a3b8;
        position: relative;
        z-index: 1;
    }
    .chat-date-divider::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        width: 100%;
        height: 1px;
        background: #e2e8f0;
        z-index: 0;
    }

    /* Messages */
    .message-row {
        display: flex;
        width: 100%;
    }
    .message-row.agent {
        justify-content: flex-start;
    }
    .message-row.user {
        justify-content: flex-end;
    }
    .message-bubble {
        max-width: 70%;
        padding: 15px 20px;
        font-size: 15px;
        line-height: 1.5;
        position: relative;
    }
    .agent .message-bubble {
        background: white;
        color: #1e293b;
        border-radius: 20px 20px 20px 4px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .user .message-bubble {
        background: #1e3a8a;
        color: white;
        border-radius: 20px 20px 4px 20px;
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.2);
    }
    .message-time {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 6px;
        display: block;
    }
    .user .message-time {
        color: #bfdbfe;
        text-align: right;
    }

    /* Typing Indicator */
    .typing-indicator {
        display: none;
        align-items: center;
        gap: 5px;
        padding: 15px 20px;
        background: white;
        border-radius: 20px 20px 20px 4px;
        border: 1px solid #e2e8f0;
        width: fit-content;
    }
    .typing-dot {
        width: 8px;
        height: 8px;
        background: #94a3b8;
        border-radius: 50%;
        animation: typing 1.4s infinite ease-in-out;
    }
    .typing-dot:nth-child(1) { animation-delay: 0s; }
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes typing {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.2); opacity: 1; }
    }

    /* Input Area */
    .chat-footer {
        background: white;
        padding: 20px 30px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .chat-input-wrapper {
        flex: 1;
        position: relative;
    }
    .chat-input {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 24px;
        padding: 14px 20px 14px 45px;
        font-size: 15px;
        font-family: 'Outfit', sans-serif;
        color: #1e293b;
        outline: none;
        transition: all 0.2s ease;
        background: #f8fafc;
    }
    .chat-input:focus {
        border-color: #3b82f6;
        background: white;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .chat-input-wrapper i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
    }
    .send-btn {
        background: #1e3a8a;
        color: white;
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 18px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.25);
    }
    .send-btn:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
    }

    /* Dark Mode */
    body.dark-mode .realtime-wrapper { background-color: #0f172a; }
    body.dark-mode .chat-container { background: #1e293b; border-color: #334155; }
    body.dark-mode .chat-body { background: #0f172a; }
    body.dark-mode .agent .message-bubble { background: #1e293b; border-color: #334155; color: #f1f5f9; }
    body.dark-mode .chat-footer { background: #1e293b; border-top-color: #334155; }
    body.dark-mode .chat-input { background: #0f172a; border-color: #334155; color: #f1f5f9; }
    body.dark-mode .chat-input:focus { border-color: #38bdf8; box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.1); }
    body.dark-mode .chat-date-divider span { background: #0f172a; color: #64748b; }
    body.dark-mode .chat-date-divider::before { background: #334155; }
    body.dark-mode .typing-indicator { background: #1e293b; border-color: #334155; }
</style>

<div class="realtime-wrapper">
    <div class="container">
        <div class="chat-container">
            <!-- Header -->
            <div class="chat-header">
                <div class="chat-header-info">
                    <div class="agent-avatar">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Chloe&style=circle&top=longHair&clothing=blazerAndShirt" alt="Support Agent">
                        <div class="status-dot"></div>
                    </div>
                    <div class="agent-details">
                        <h2>H-Mart Premiere Support</h2>
                        <p>Typically replies instantly</p>
                    </div>
                </div>
                <div class="chat-header-actions">
                    <button title="Voice Call (Coming Soon)"><i class="fa fa-phone"></i></button>
                    <button title="Video Call (Coming Soon)"><i class="fa fa-video-camera"></i></button>
                    <button title="More Options"><i class="fa fa-ellipsis-v"></i></button>
                </div>
            </div>

            <!-- Body -->
            <div class="chat-body" id="chatBody">
                <div class="chat-date-divider">
                    <span>Today, <?php echo date('h:i A'); ?></span>
                </div>
                
                <div class="message-row agent">
                    <div class="message-bubble">
                        Hello! Welcome to your exclusive Premiere Real-Time Support channel. ✨<br><br>
                        My name is Chloe. How can I assist you with your premium orders or exclusive access today?
                        <span class="message-time"><?php echo date('h:i A'); ?></span>
                    </div>
                </div>

                <div class="message-row agent typing-indicator" id="typingIndicator">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>

            <!-- Footer -->
            <div class="chat-footer">
                <div class="chat-input-wrapper">
                    <i class="fa fa-smile-o"></i>
                    <input type="text" id="chatInput" class="chat-input" placeholder="Type your message here..." onkeypress="handleEnter(event)">
                </div>
                <button class="send-btn" onclick="sendMessage()"><i class="fa fa-paper-plane"></i></button>
            </div>
        </div>
    </div>
</div>

<script>
    function handleEnter(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    }

    function sendMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        if (!message) return;

        // Add user message
        const chatBody = document.getElementById('chatBody');
        const typingIndicator = document.getElementById('typingIndicator');
        const timeString = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        const userHtml = `
            <div class="message-row user" style="animation: fadeIn 0.3s ease;">
                <div class="message-bubble">
                    ${message}
                    <span class="message-time">${timeString}</span>
                </div>
            </div>
        `;
        
        // Insert before typing indicator
        typingIndicator.insertAdjacentHTML('beforebegin', userHtml);
        input.value = '';
        chatBody.scrollTop = chatBody.scrollHeight;

        // Show typing indicator
        typingIndicator.style.display = 'flex';
        chatBody.scrollTop = chatBody.scrollHeight;

        // Simulate agent response
        setTimeout(() => {
            typingIndicator.style.display = 'none';
            
            const agentResponses = [
                "That's a great question! As a Premiere member, you have priority access to all our limited stock items.",
                "I've just checked your account, and your Premiere benefits are fully active! Is there a specific premium item you're looking for?",
                "I'm opening a real-time tracking session for your latest order now. It looks like it's on the way!",
                "Absolutely. I can process that request for you right away with zero priority fees.",
                "Thank you for being a Premiere member! Let me handle that immediately for you."
            ];
            
            const randomResponse = agentResponses[Math.floor(Math.random() * agentResponses.length)];
            
            const agentHtml = `
                <div class="message-row agent" style="animation: fadeIn 0.3s ease;">
                    <div class="message-bubble">
                        ${randomResponse}
                        <span class="message-time">${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span>
                    </div>
                </div>
            `;
            
            typingIndicator.insertAdjacentHTML('beforebegin', agentHtml);
            chatBody.scrollTop = chatBody.scrollHeight;
            
        }, 1500 + Math.random() * 1000);
    }
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
