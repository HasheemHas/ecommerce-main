<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    .aishopper-wrapper {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
        height: calc(100vh - 85px); /* Adjust based on navbar height */
        display: flex;
        flex-direction: row;
        width: 100%;
        overflow: hidden;
    }
    
    /* Sidebar */
    .shopper-sidebar {
        width: 280px;
        background: #1e293b;
        color: white;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        border-right: 1px solid #334155;
        transition: margin-left 0.3s ease;
    }
    .aishopper-wrapper.sidebar-collapsed .shopper-sidebar {
        margin-left: -280px;
    }
    .aishopper-wrapper:not(.sidebar-collapsed) .shopper-header .sidebar-toggle-btn {
        display: none;
    }
    
    .sidebar-toggle-btn {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 18px;
        cursor: pointer;
        padding: 5px;
        margin-right: 15px;
        transition: color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sidebar-toggle-btn:hover { color: #1e293b; }
    body.dark-mode .sidebar-toggle-btn:hover { color: white; }

    .sidebar-header {
        padding: 20px;
        border-bottom: 1px solid #334155;
    }
    .new-chat-btn {
        width: 100%;
        background: transparent;
        border: 1px solid #475569;
        color: white;
        padding: 12px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-size: 13px;
        font-family: 'Outfit', sans-serif;
        transition: all 0.2s;
    }
    .new-chat-btn:hover { background: #334155; }
    
    .sidebar-search {
        width: 100%;
        background: rgba(0,0,0,0.2);
        border: 1px solid #334155;
        color: white;
        padding: 10px 12px 10px 32px;
        border-radius: 8px;
        font-size: 13px;
        outline: none;
        transition: all 0.2s;
    }
    .sidebar-search:focus {
        border-color: #3b82f6;
        background: rgba(0,0,0,0.3);
    }
    
    .sidebar-history {
        flex: 1;
        overflow-y: auto;
        padding: 20px 10px;
    }
    .history-group { margin-bottom: 20px; }
    .history-label {
        font-size: 11px;
        color: #94a3b8;
        padding: 0 10px;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .history-item {
        padding: 10px;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #cbd5e1;
        font-size: 14px;
        transition: all 0.2s;
        margin-bottom: 2px;
    }
    .history-item.active { background: #334155; color: white; }
    .history-item:hover:not(.active) { background: #273549; }
    .history-item i { color: #94a3b8; }
    
    .history-item-title {
        flex: 1;
        white-space: nowrap; 
        overflow: hidden; 
        text-overflow: ellipsis;
    }
    .history-actions {
        display: none;
        gap: 5px;
    }
    .history-item:hover .history-actions {
        display: flex;
    }
    .history-action-btn {
        background: transparent;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 2px 4px;
        font-size: 12px;
        transition: color 0.2s;
    }
    .history-action-btn:hover { color: white; }
    .history-action-btn.star:hover { color: #eab308; }
    .history-action-btn.delete:hover { color: #ef4444; }
    .history-action-btn.rename:hover { color: #3b82f6; }

    .sidebar-api-usage {
        margin-top: auto;
        padding: 15px 20px;
        border-top: 1px solid #334155;
        font-size: 12px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .api-usage-count {
        font-weight: 700;
        color: #38bdf8;
    }

    /* Main Chat Container */
    .shopper-container {
        width: 100%;
        background: white;
        display: flex;
        flex-direction: column;
        flex: 1;
        overflow: hidden;
    }
    
    /* Header */
    .shopper-header {
        background: white;
        padding: 15px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #1e293b;
        border-bottom: 1px solid #e2e8f0;
        flex-shrink: 0;
    }
    .header-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .header-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .ai-logo-light { display: block; width: 100%; height: 100%; object-fit: contain; }
    .ai-logo-dark { display: none; width: 100%; height: 100%; object-fit: contain; }
    body.dark-mode .ai-logo-light { display: none; }
    body.dark-mode .ai-logo-dark { display: block; }
    .header-details h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
    }
    .header-details p {
        margin: 2px 0 0 0;
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }

    /* Body */
    .shopper-body {
        flex: 1;
        background: #f8fafc;
        padding: 40px 40px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 25px;
    }
    
    /* Prompts */
    .suggested-prompts {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 30px;
        justify-content: center;
    }
    .prompt-pill {
        background: white;
        border: 1px solid #cbd5e1;
        padding: 10px 20px;
        border-radius: 24px;
        font-size: 15px;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 500;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .prompt-pill:hover {
        background: #ede9fe;
        border-color: #8b5cf6;
        color: #6d28d9;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(139, 92, 246, 0.15);
    }

    /* Messages */
    .msg-row {
        display: flex;
        width: 100%;
        animation: fadeIn 0.3s ease;
    }
    .msg-row.ai { justify-content: flex-start; }
    .msg-row.user { justify-content: flex-end; }
    .msg-bubble {
        max-width: 80%;
        padding: 18px 25px;
        font-size: 14px;
        line-height: 1.6;
        position: relative;
    }
    .ai .msg-bubble {
        background: white;
        color: #1e293b;
        border-radius: 24px 24px 24px 6px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    }
    .user .msg-bubble {
        background: #4f46e5;
        color: white;
        border-radius: 24px 24px 6px 24px;
        box-shadow: 0 6px 20px rgba(79, 70, 229, 0.25);
    }

    /* Product Cards inside Chat */
    .product-carousel {
        display: flex;
        gap: 15px;
        margin-top: 20px;
        overflow-x: auto;
        padding-bottom: 15px;
    }
    .product-card {
        min-width: 180px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px;
        text-align: center;
        transition: all 0.2s;
    }
    .product-card:hover {
        border-color: #8b5cf6;
        background: white;
        box-shadow: 0 10px 25px rgba(139, 92, 246, 0.1);
        transform: translateY(-4px);
    }
    .product-card img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 12px;
    }
    .product-card h4 {
        margin: 0;
        font-size: 14px;
        color: #1e293b;
        font-weight: 600;
    }
    .product-card .price {
        color: #6366f1;
        font-weight: 800;
        font-size: 16px;
        margin: 8px 0;
    }
    .product-card button {
        background: #4f46e5;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        margin-top: 5px;
        transition: background 0.2s;
    }
    .product-card button:hover { background: #4338ca; }

    /* Typing Indicator */
    .typing {
        display: none;
        padding: 20px 25px;
        background: white;
        border-radius: 24px 24px 24px 6px;
        border: 1px solid #e2e8f0;
        width: fit-content;
        gap: 6px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    }
    .typing span {
        width: 10px; height: 10px;
        background: #8b5cf6;
        border-radius: 50%;
        animation: typing 1.4s infinite ease-in-out;
    }
    .typing span:nth-child(1) { animation-delay: 0s; }
    .typing span:nth-child(2) { animation-delay: 0.2s; }
    .typing span:nth-child(3) { animation-delay: 0.4s; }

    /* Footer Input */
    .shopper-footer {
        background: white;
        padding: 25px 40px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 15px;
        flex-shrink: 0;
    }
    .attach-btn {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        color: #64748b;
        border: none;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 22px;
        transition: all 0.2s;
        z-index: 10;
    }
    .attach-btn:hover { background: #e2e8f0; color: #4f46e5; }
    body.dark-mode .attach-btn { color: #94a3b8; }
    body.dark-mode .attach-btn:hover { background: #334155; color: #8b5cf6; }

    .input-wrapper {
        flex: 1;
        position: relative;
        display: flex;
        align-items: center;
    }
    .shopper-input {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 30px;
        padding: 18px 25px 18px 50px; /* tighter padding for attach button */
        font-size: 16px;
        font-family: 'Outfit', sans-serif;
        outline: none;
        background: #f1f5f9;
        transition: all 0.2s;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    .shopper-input:focus {
        border-color: #8b5cf6;
        background: white;
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
    }
    .send-btn {
        background: #4f46e5;
        color: white;
        border: none;
        width: 58px;
        height: 58px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 22px;
        transition: all 0.2s;
        box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
        flex-shrink: 0;
    }
    .send-btn:hover { background: #4338ca; transform: translateY(-3px); box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4); }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes typing {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.2); opacity: 1; }
    }
    
    /* Dark Mode Support */
    body.dark-mode .aishopper-wrapper { background-color: #0f172a; }
    
    body.dark-mode .shopper-sidebar { background: #0b1121; border-color: #1e293b; }
    body.dark-mode .sidebar-header { border-color: #1e293b; }
    body.dark-mode .new-chat-btn { border-color: #334155; }
    body.dark-mode .new-chat-btn:hover { background: #1e293b; }
    body.dark-mode .history-item.active { background: #1e293b; }
    body.dark-mode .history-item:hover:not(.active) { background: #151f32; }
    
    body.dark-mode .shopper-container { background: #0f172a; }
    body.dark-mode .shopper-header { background: #0f172a; color: #f1f5f9; border-color: #1e293b; }
    body.dark-mode .header-details h2 { color: #f1f5f9; }
    body.dark-mode .shopper-body { background: #0f172a; }
    
    body.dark-mode .prompt-pill { background: #1e293b; border-color: #334155; color: #94a3b8; }
    body.dark-mode .prompt-pill:hover { background: #312e81; border-color: #4f46e5; color: #c7d2fe; }
    
    body.dark-mode .ai .msg-bubble { background: #1e293b; color: #f1f5f9; border-color: #334155; box-shadow: none; }
    body.dark-mode .typing { background: #1e293b; border-color: #334155; }
    
    body.dark-mode .shopper-footer { background: #0f172a; border-color: #1e293b; }
    body.dark-mode .shopper-input { background: #1e293b; border-color: #334155; color: #f1f5f9; }
    body.dark-mode .shopper-input:focus { border-color: #6366f1; background: #0f172a; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2); }
    
    body.dark-mode .product-card { background: #1e293b; border-color: #334155; }
    body.dark-mode .product-card:hover { background: #0f172a; border-color: #4f46e5; }
    body.dark-mode .product-card h4 { color: #f1f5f9; }
    /* Hide Global Footer on this page */
    #footer, footer, section#footer { display: none !important; }

    /* Toast Notification styles */
    .aishopper-toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: #1e293b;
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 9999;
        font-family: 'Outfit', sans-serif;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .aishopper-toast.show {
        transform: translateY(0);
        opacity: 1;
    }
    .aishopper-toast.success { border-left: 4px solid #10b981; }
    .aishopper-toast.warning { border-left: 4px solid #f59e0b; }
    .aishopper-toast.error { border-left: 4px solid #ef4444; }

    /* Message bubble actions */
    .msg-bubble {
        position: relative;
    }
    .msg-actions {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        display: none;
        gap: 6px;
        z-index: 10;
        background: white;
        border: 1px solid #cbd5e1;
        padding: 4px 8px;
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    body.dark-mode .msg-actions {
        background: #1e293b;
        border-color: #334155;
    }
    .msg-row.user .msg-actions {
        left: -65px;
        right: auto;
    }
    .msg-row.ai .msg-actions {
        right: -65px;
        left: auto;
    }
    .msg-bubble:hover .msg-actions {
        display: flex;
    }
    .msg-action-btn {
        background: transparent;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 2px;
        font-size: 11px;
        transition: color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
    }
    .msg-action-btn:hover {
        color: #4f46e5;
    }
    body.dark-mode .msg-action-btn:hover {
        color: #8b5cf6;
    }
    .msg-action-btn.delete:hover {
        color: #ef4444 !important;
    }
    
    /* Inline Editing styles */
    .edit-textarea {
        width: 100%;
        min-width: 250px;
        min-height: 60px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 8px;
        font-family: inherit;
        font-size: 14px;
        resize: vertical;
        outline: none;
        margin-bottom: 8px;
        color: #1e293b;
    }
    body.dark-mode .edit-textarea {
        background: #0f172a;
        color: #f1f5f9;
        border-color: #334155;
    }
    .edit-btn-group {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }
    .edit-save-btn {
        background: #4f46e5;
        color: white;
        border: none;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }
    .edit-cancel-btn {
        background: #e2e8f0;
        color: #475569;
        border: none;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }
    body.dark-mode .edit-cancel-btn {
        background: #334155;
        color: #cbd5e1;
    }
    #modelSelector {
        color: #1e293b;
    }
    body.dark-mode #modelSelector {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #f1f5f9 !important;
    }

    .header-search-form input:focus {
        border-color: #1e3a8a !important;
        background-color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    body.dark-mode .header-search-form input {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    body.dark-mode .header-search-form input:focus {
        border-color: #38bdf8 !important;
        background-color: #0f172a !important;
    }
</style>

<div class="aishopper-wrapper">
    <!-- Sidebar -->
    <div class="shopper-sidebar">
        <div class="sidebar-header">
            <div style="display: flex; gap: 10px; align-items: center;">
                <button class="sidebar-toggle-btn" onclick="toggleSidebar()" title="Toggle Sidebar" style="margin: 0; padding: 10px; border-radius: 8px; border: 1px solid #334155;">
                    <i class="fa fa-navicon"></i>
                </button>
                <button class="new-chat-btn" onclick="startNewSession()" style="flex: 1;">
                    <i class="fa fa-plus"></i> New Shopping Session
                </button>
            </div>
            <div class="sidebar-search-wrapper" style="position: relative; margin-top: 15px;">
                <i class="fa fa-search sidebar-search-icon" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
                <input type="text" id="chatSearchInput" class="sidebar-search" placeholder="Search chats..." oninput="renderSidebar(this.value)">
            </div>
        </div>
        <div class="sidebar-history" id="sidebarHistory">
            <!-- Dynamically populated by JS -->
        </div>
        <div class="sidebar-api-usage">
            <span><i class="fa fa-bolt" style="color: #38bdf8; margin-right: 5px;"></i> API Usage</span>
            <span class="api-usage-count" id="apiUsageCount">0 msgs</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="shopper-container">
        <!-- Header -->
        <div class="shopper-header">
            <div class="header-info">
                <button class="sidebar-toggle-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
                    <i class="fa fa-navicon"></i>
                </button>
                <div class="ai-avatar">
                    <img src="img/hmart-bag-logo.svg" class="ai-logo-light" alt="H-Mart AI">
                    <img src="img/hmart-bag-logo-dark.svg" class="ai-logo-dark" alt="H-Mart AI">
                </div>
                <div class="header-details">
                    <h2>H-Mart AI Assistant</h2>
                    <p>Powered by advanced shopping intelligence</p>
                </div>
            </div>
            <!-- Header Search Option -->
            <form action="index.php?q=product" method="POST" class="header-search-form" style="width: 300px; max-width: 100%; margin: 0;">
                <div class="header-search-wrapper" style="position: relative; width: 100%;">
                    <i class="fa fa-search" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 14px; pointer-events: none;"></i>
                    <input type="text" name="search" id="headerSearchInputVal" placeholder="Search H-Mart catalog..." style="width: 100%; height: 42px; padding: 0 16px 0 40px; border-radius: 20px; border: 1px solid #cbd5e1; background-color: #f8fafc; font-family: 'Outfit', sans-serif; font-size: 13.5px; font-weight: 500; outline: none; transition: all 0.2s;">
                </div>
            </form>
        </div>

        <!-- Body -->
        <div class="shopper-body" id="chatBody">
            <div class="suggested-prompts" id="promptsArea">
                <div class="prompt-pill" onclick="sendPrompt('Find me ingredients for Spaghetti Bolognese')">🍝 Ingredients for Spaghetti</div>
                <div class="prompt-pill" onclick="sendPrompt('Show me the best organic fruits')">🍎 Organic Fruits</div>
                <div class="prompt-pill" onclick="sendPrompt('I need snacks for a movie night under 500 Rs')">🍿 Movie Snacks</div>
                <div class="prompt-pill" onclick="sendPrompt('Recommend some healthy breakfast items')">🥑 Healthy Breakfast</div>
            </div>

            <div class="msg-row ai">
                <div class="msg-bubble">
                    Hello! 👋 I'm your AI Personal Shopper.<br><br>
                    I can help you discover products, find recipes and ingredients, or curate a shopping list based on your budget. What are you looking for today?
                </div>
            </div>

            <div class="msg-row ai typing" id="typingIndicator" style="display: none;">
                <span></span><span></span><span></span>
            </div>
        </div>

        <!-- Footer -->
        <div class="shopper-footer" style="gap: 12px;">
            <select id="modelSelector" style="width: 215px; flex-shrink: 0; height: 58px; padding: 0 24px 0 16px; margin: 0; border-radius: 30px; background-color: #f1f5f9; border: 1px solid #cbd5e1; font-family: 'Outfit', sans-serif; font-weight: 600; font-size: 13px; outline: pointer;" onchange="saveModelPreference(this.value)">
                <option value="meta/llama-3.1-8b-instruct">Llama 3.1 8B (Instant)</option>
                <option value="meta/llama-3.3-70b-instruct">Llama 3.3 70B (Smart)</option>
                <option value="meta/llama-3.2-11b-vision-instruct">Llama 3.2 11B (Balanced)</option>
            </select>
            <div class="input-wrapper">
                <button class="attach-btn" title="Add photos, videos, or files" onclick="document.getElementById('fileUpload').click()">
                    <i class="fa fa-plus"></i>
                </button>
                <input type="file" id="fileUpload" style="display: none;" multiple onchange="handleFileUpload(event)">
                <input type="text" id="chatInput" class="shopper-input" placeholder="Ask me to find anything..." onkeypress="handleEnter(event)">
            </div>
            <button class="send-btn" style="background-color: #64748b; margin-right: 2px;" onclick="searchCatalogFromChat()" title="Search catalog"><i class="fa fa-search"></i></button>
            <button class="send-btn" onclick="sendMessage()" title="Send to AI"><i class="fa fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<script>
    let sessions = JSON.parse(localStorage.getItem('hmart_ai_sessions')) || [];
    let currentSessionId = null;

    // Generate a unique ID for a session
    function generateId() {
        return Math.random().toString(36).substring(2, 9);
    }

    // Load or create a session on startup
    function init() {
        const preferredModel = localStorage.getItem('hmart_selected_model') || 'meta/llama-3.1-8b-instruct';
        const selector = document.getElementById('modelSelector');
        if (selector) selector.value = preferredModel;

        if (sessions.length > 0) {
            loadSession(sessions[0].id);
        } else {
            startNewSession();
        }
        renderSidebar();
    }

    function saveModelPreference(value) {
        localStorage.setItem('hmart_selected_model', value);
    }

    // Start a brand new session
    function startNewSession() {
        currentSessionId = generateId();
        const newSession = {
            id: currentSessionId,
            title: 'New Shopping Session',
            timestamp: Date.now(),
            messages: []
        };
        sessions.unshift(newSession);
        saveSessions();
        renderSidebar();
        clearChatUI();
    }

    // Load a specific session by ID
    function loadSession(id) {
        currentSessionId = id;
        const session = sessions.find(s => s.id === id);
        if (session) {
            renderSidebar();
            renderChat(session.messages);
        }
    }

    // Clear the chat UI and show the default welcome message
    function clearChatUI() {
        const chatBody = document.getElementById('chatBody');
        chatBody.innerHTML = `
            <div class="suggested-prompts" id="promptsArea">
                <div class="prompt-pill" onclick="sendPrompt('Find me ingredients for Spaghetti Bolognese')">🍝 Ingredients for Spaghetti</div>
                <div class="prompt-pill" onclick="sendPrompt('Show me the best organic fruits')">🍎 Organic Fruits</div>
                <div class="prompt-pill" onclick="sendPrompt('I need snacks for a movie night under 500 Rs')">🍿 Movie Snacks</div>
                <div class="prompt-pill" onclick="sendPrompt('Recommend some healthy breakfast items')">🥑 Healthy Breakfast</div>
            </div>
            <div class="msg-row ai">
                <div class="msg-bubble">
                    Hello! 👋 I'm your AI Personal Shopper.<br><br>
                    I can help you discover products, find recipes and ingredients, or curate a shopping list based on your budget. What are you looking for today?
                </div>
            </div>
            <div class="msg-row ai typing" id="typingIndicator" style="display: none;">
                <span></span><span></span><span></span>
            </div>
        `;
    }

    // Render old messages when loading a session
    function renderChat(messages) {
        clearChatUI();
        if (messages.length > 0) {
            document.getElementById('promptsArea').style.display = 'none';
        }
        
        const typingIndicator = document.getElementById('typingIndicator');
        messages.forEach((msg, index) => {
            const rowClass = msg.role === 'user' ? 'user' : 'ai';
            
            let productsHtml = '';
            if (msg.products && msg.products.length > 0) {
                productsHtml += `<div class="product-carousel">`;
                msg.products.forEach(p => {
                    productsHtml += `
                        <div class="product-card">
                            <a href="${p.url}" style="text-decoration: none; color: inherit;">
                                <img src="${p.image}" alt="${p.name}">
                                <h4>${p.name}</h4>
                            </a>
                            <div class="price">₹${parseFloat(p.price).toFixed(2)}</div>
                            <button onclick="buyNow(${p.id}, ${p.price})">Buy Now</button>
                        </div>
                    `;
                });
                productsHtml += `</div>`;
            }

            let imageHtml = '';
            if (msg.generated_image_url) {
                imageHtml = `
                    <div style="margin-top: 12px; border-radius: 12px; overflow: hidden; border: 1px solid #cbd5e1; box-shadow: 0 4px 15px rgba(0,0,0,0.05); max-width: 400px;">
                        <img src="${msg.generated_image_url}" alt="Generated Image" style="width: 100%; height: auto; display: block;" onerror="this.style.display='none'">
                    </div>
                `;
            }

            let actionsHtml = '';
            if (msg.role === 'user') {
                actionsHtml = `
                    <div class="msg-actions">
                        <button class="msg-action-btn" onclick="triggerEditMessage(${index})" title="Edit Message"><i class="fa fa-pencil"></i></button>
                        <button class="msg-action-btn delete" onclick="triggerDeleteMessage(${index})" title="Delete Message"><i class="fa fa-trash"></i></button>
                    </div>
                `;
            } else {
                actionsHtml = `
                    <div class="msg-actions">
                        <button class="msg-action-btn delete" onclick="triggerDeleteMessage(${index})" title="Delete Message"><i class="fa fa-trash"></i></button>
                    </div>
                `;
            }

            const html = `
                <div class="msg-row ${rowClass}">
                    <div class="msg-bubble" id="msg-bubble-${index}">
                        <div class="msg-text">${msg.content}</div>
                        ${productsHtml}
                        ${imageHtml}
                        ${actionsHtml}
                    </div>
                </div>
            `;
            typingIndicator.insertAdjacentHTML('beforebegin', html);
        });
        const chatBody = document.getElementById('chatBody');
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    // Render the sidebar history list
    function renderSidebar(searchQuery = '') {
        const sidebar = document.getElementById('sidebarHistory');
        let html = '';

        let filteredSessions = sessions;
        if (searchQuery.trim() !== '') {
            const lowerQuery = searchQuery.toLowerCase();
            filteredSessions = sessions.filter(s => s.title.toLowerCase().includes(lowerQuery));
        }

        const starred = filteredSessions.filter(s => s.isStarred);
        const unstarred = filteredSessions.filter(s => !s.isStarred);

        if (starred.length > 0) {
            html += '<div class="history-group"><div class="history-label">Starred Sessions</div>';
            starred.forEach(session => {
                const isActive = session.id === currentSessionId ? 'active' : '';
                html += `
                    <div class="history-item ${isActive}">
                        <i class="fa fa-star" style="color: #eab308;"></i> 
                        <span class="history-item-title" onclick="loadSession('${session.id}')" title="${session.title}">${session.title}</span>
                        <div class="history-actions">
                            <button class="history-action-btn rename" onclick="renameSession('${session.id}')" title="Rename"><i class="fa fa-pencil"></i></button>
                            <button class="history-action-btn star" onclick="toggleStarSession('${session.id}')" title="Unstar"><i class="fa fa-star"></i></button>
                            <button class="history-action-btn delete" onclick="deleteSession('${session.id}')" title="Delete"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
        }

        if (unstarred.length > 0) {
            html += '<div class="history-group"><div class="history-label">Recent Sessions</div>';
            unstarred.forEach(session => {
                const isActive = session.id === currentSessionId ? 'active' : '';
                html += `
                    <div class="history-item ${isActive}">
                        <i class="fa fa-shopping-bag"></i> 
                        <span class="history-item-title" onclick="loadSession('${session.id}')" title="${session.title}">${session.title}</span>
                        <div class="history-actions">
                            <button class="history-action-btn rename" onclick="renameSession('${session.id}')" title="Rename"><i class="fa fa-pencil"></i></button>
                            <button class="history-action-btn star" onclick="toggleStarSession('${session.id}')" title="Star"><i class="fa fa-star-o"></i></button>
                            <button class="history-action-btn delete" onclick="deleteSession('${session.id}')" title="Delete"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
        }

        sidebar.innerHTML = html;
        updateApiUsageUI();
    }

    function renameSession(id) {
        const session = sessions.find(s => s.id === id);
        if (session) {
            const newName = prompt("Enter new name for this session:", session.title);
            if (newName && newName.trim() !== '') {
                session.title = newName.trim();
                saveSessions();
                renderSidebar();
            }
        }
    }

    function deleteSession(id) {
        if (confirm("Are you sure you want to delete this session?")) {
            sessions = sessions.filter(s => s.id !== id);
            if (currentSessionId === id) {
                if (sessions.length > 0) {
                    loadSession(sessions[0].id);
                } else {
                    startNewSession();
                }
            } else {
                saveSessions();
                renderSidebar();
            }
        }
    }

    function toggleStarSession(id) {
        const session = sessions.find(s => s.id === id);
        if (session) {
            session.isStarred = !session.isStarred;
            saveSessions();
            renderSidebar();
        }
    }

    // Save sessions to localStorage
    function saveSessions() {
        localStorage.setItem('hmart_ai_sessions', JSON.stringify(sessions));
    }

    // API Usage Tracking
    let apiUsageCount = parseInt(localStorage.getItem('hmart_ai_api_usage')) || 0;
    
    function updateApiUsageUI() {
        const countEl = document.getElementById('apiUsageCount');
        if (countEl) {
            countEl.innerText = apiUsageCount.toLocaleString() + ' tokens';
        }
    }

    function incrementApiUsage(tokens = 0) {
        apiUsageCount += tokens;
        localStorage.setItem('hmart_ai_api_usage', apiUsageCount);
        updateApiUsageUI();
    }

    function handleEnter(e) {
        if (e.key === 'Enter') sendMessage();
    }

    function sendPrompt(text) {
        document.getElementById('chatInput').value = text;
        const promptsArea = document.getElementById('promptsArea');
        if (promptsArea) promptsArea.style.display = 'none';
        sendMessage();
    }

    function handleFileUpload(event) {
        const files = event.target.files;
        if (files.length > 0) {
            const input = document.getElementById('chatInput');
            // Check if there's already text to append, else just show the filename
            const fileNameStr = Array.from(files).map(f => `[📎 ${f.name}]`).join(' ');
            if (input.value.trim() === '') {
                input.value = fileNameStr + ' ';
            } else {
                input.value += ' ' + fileNameStr + ' ';
            }
            input.focus();
        }
    }

    // Custom Toast Notification Function
    function showToast(title, message, type = 'success') {
        let toast = document.getElementById('aishopper-toast-element');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'aishopper-toast-element';
            toast.className = 'aishopper-toast';
            document.body.appendChild(toast);
        }
        
        toast.className = `aishopper-toast ${type}`;
        let iconHtml = '<i class="fa fa-info-circle"></i>';
        if (type === 'success') iconHtml = '<i class="fa fa-check-circle" style="color: #10b981;"></i>';
        if (type === 'warning') iconHtml = '<i class="fa fa-exclamation-circle" style="color: #f59e0b;"></i>';
        if (type === 'error') iconHtml = '<i class="fa fa-times-circle" style="color: #ef4444;"></i>';
        
        toast.innerHTML = `
            ${iconHtml}
            <div>
                <strong style="display:block; font-size: 14px;">${title}</strong>
                <span style="font-size: 13px; color: #94a3b8;">${message}</span>
            </div>
        `;
        
        // Trigger show animation
        setTimeout(() => toast.classList.add('show'), 10);
        
        // Hide after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    // AJAX Buy Now call
    async function buyNow(productId, price) {
        try {
            const response = await fetch('add_to_cart_ajax.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    productId: productId,
                    price: price,
                    qty: 1
                })
            });
            const result = await response.json();
            if (result.success) {
                showToast("Success", result.message, "success");
                
                // Update badge cart count dynamically
                const cartBadge = document.querySelector('.nav-cart-badge-count');
                if (cartBadge) {
                    cartBadge.textContent = result.cartCount;
                    cartBadge.style.display = 'flex';
                } else {
                    const cartIcon = document.querySelector('.nav-cart div');
                    if (cartIcon) {
                        cartIcon.insertAdjacentHTML('beforeend', `<span class="nav-cart-badge-count">${result.cartCount}</span>`);
                    }
                }
                
                // Redirect to the cart page
                setTimeout(() => {
                    window.location.href = 'index.php?q=cart';
                }, 800);
            } else {
                if (result.error === 'login_required') {
                    showToast("Login Required", "Redirecting to login...", "warning");
                    setTimeout(() => {
                        window.location.href = 'index.php?q=login';
                    }, 1500);
                } else {
                    showToast("Error", result.message || "Could not add item to cart.", "error");
                }
            }
        } catch (error) {
            console.error("Error adding to cart:", error);
            showToast("Error", "Network error occurred. Please try again.", "error");
        }
    }

    // Message Deletion Handler
    function triggerDeleteMessage(index) {
        let session = sessions.find(s => s.id === currentSessionId);
        if (!session) return;
        
        if (confirm("Are you sure you want to delete this message?")) {
            // Delete user message and corresponding assistant response if it exists
            if (session.messages[index] && session.messages[index].role === 'user') {
                session.messages.splice(index, 1);
                if (session.messages[index] && session.messages[index].role === 'assistant') {
                    session.messages.splice(index, 1);
                }
            } else {
                session.messages.splice(index, 1);
            }
            saveSessions();
            renderChat(session.messages);
            showToast("Success", "Message deleted.", "success");
        }
    }

    // Message Editing Handlers
    function triggerEditMessage(index) {
        let session = sessions.find(s => s.id === currentSessionId);
        if (!session) return;
        
        const bubble = document.getElementById(`msg-bubble-${index}`);
        if (!bubble) return;
        
        const originalText = session.messages[index].content;
        
        bubble.innerHTML = `
            <textarea class="edit-textarea" id="edit-textarea-${index}">${originalText}</textarea>
            <div class="edit-btn-group">
                <button class="edit-cancel-btn" onclick="cancelEditMessage(${index})">Cancel</button>
                <button class="edit-save-btn" onclick="saveEditMessage(${index})">Save & Resend</button>
            </div>
        `;
    }
    
    function cancelEditMessage(index) {
        let session = sessions.find(s => s.id === currentSessionId);
        if (session) {
            renderChat(session.messages);
        }
    }
    
    async function saveEditMessage(index) {
        let session = sessions.find(s => s.id === currentSessionId);
        if (!session) return;
        
        const textarea = document.getElementById(`edit-textarea-${index}`);
        if (!textarea) return;
        
        const newText = textarea.value.trim();
        if (!newText) return;
        
        session.messages[index].content = newText;
        // Truncate following conversation history from this edit point
        session.messages = session.messages.slice(0, index + 1);
        saveSessions();
        renderChat(session.messages);
        
        const chatBody = document.getElementById('chatBody');
        const typingIndicator = document.getElementById('typingIndicator');
        typingIndicator.style.display = 'flex';
        chatBody.scrollTop = chatBody.scrollHeight;
        
        try {
            const selectedModel = document.getElementById('modelSelector') ? document.getElementById('modelSelector').value : 'meta/llama-3.1-8b-instruct';
            const response = await fetch('api_chat.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    message: newText,
                    history: session.messages.slice(0, index), // send history prior to this edited message
                    model: selectedModel
                })
            });
            
            const data = await response.json();
            typingIndicator.style.display = 'none';
            
            let aiText = "I'm sorry, I encountered an error connecting to the AI core.";
            let products = [];
            
            if (data.choices && data.choices[0] && data.choices[0].message) {
                aiText = data.choices[0].message.content;
                products = data.products || [];
                const imgUrl = data.generated_image_url || null;
                session.messages.push({ role: 'assistant', content: aiText, products: products, generated_image_url: imgUrl });
                saveSessions();
                
                if (data.usage && data.usage.total_tokens) {
                    incrementApiUsage(data.usage.total_tokens);
                }
            } else if (data.error) {
                console.error("API Error:", data.error);
            }
            
            renderChat(session.messages);
            
        } catch (error) {
            console.error("Fetch Error:", error);
            typingIndicator.style.display = 'none';
            showToast("Error", "Failed to retrieve AI response.", "error");
        }
    }

    async function sendMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        if (!message) return;

        const chatBody = document.getElementById('chatBody');
        const typingIndicator = document.getElementById('typingIndicator');
        const promptsArea = document.getElementById('promptsArea');
        if (promptsArea) promptsArea.style.display = 'none';

        // Add user message to UI
        const userHtml = `
            <div class="msg-row user">
                <div class="msg-bubble">${message}</div>
            </div>
        `;
        typingIndicator.insertAdjacentHTML('beforebegin', userHtml);
        input.value = '';
        chatBody.scrollTop = chatBody.scrollHeight;
        typingIndicator.style.display = 'flex';
        chatBody.scrollTop = chatBody.scrollHeight;

        // Save user message to current session
        let session = sessions.find(s => s.id === currentSessionId);
        if (!session) return;
        
        // Update session title if it's the first message
        if (session.messages.length === 0) {
            session.title = message.substring(0, 30) + (message.length > 30 ? '...' : '');
            renderSidebar();
        }
        
        session.messages.push({ role: 'user', content: message });
        saveSessions();

        try {
            // Call Llama API via proxy
            const selectedModel = document.getElementById('modelSelector') ? document.getElementById('modelSelector').value : 'meta/llama-3.1-8b-instruct';
            const response = await fetch('api_chat.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    message: message,
                    history: session.messages.slice(-10), // Send last 10 messages for context
                    model: selectedModel
                })
            });
            
            const data = await response.json();
            typingIndicator.style.display = 'none';
            
            let aiText = "I'm sorry, I encountered an error connecting to the AI core.";
            let products = [];
            
            if (data.choices && data.choices[0] && data.choices[0].message) {
                aiText = data.choices[0].message.content;
                products = data.products || [];
                const imgUrl = data.generated_image_url || null;
                session.messages.push({ role: 'assistant', content: aiText, products: products, generated_image_url: imgUrl });
                saveSessions();
                
                // Track actual real-time token usage from API
                if (data.usage && data.usage.total_tokens) {
                    incrementApiUsage(data.usage.total_tokens);
                }
            } else if (data.error) {
                console.error("API Error:", data.error, data.details);
            }

            let productsHtml = '';
            if (products.length > 0) {
                productsHtml += `<div class="product-carousel">`;
                products.forEach(p => {
                    productsHtml += `
                        <div class="product-card">
                            <a href="${p.url}" style="text-decoration: none; color: inherit;">
                                <img src="${p.image}" alt="${p.name}">
                                <h4>${p.name}</h4>
                            </a>
                            <div class="price">₹${parseFloat(p.price).toFixed(2)}</div>
                            <button onclick="buyNow(${p.id}, ${p.price})">Buy Now</button>
                        </div>
                    `;
                });
                productsHtml += `</div>`;
            }

            let imageHtml = '';
            const lastMsg = session.messages[session.messages.length - 1];
            if (lastMsg && lastMsg.generated_image_url) {
                imageHtml = `
                    <div style="margin-top: 12px; border-radius: 12px; overflow: hidden; border: 1px solid #cbd5e1; box-shadow: 0 4px 15px rgba(0,0,0,0.05); max-width: 400px;">
                        <img src="${lastMsg.generated_image_url}" alt="Generated Image" style="width: 100%; height: auto; display: block;" onerror="this.style.display='none'">
                    </div>
                `;
            }

            const aiHtml = `
                <div class="msg-row ai">
                    <div class="msg-bubble">
                        ${aiText}
                        ${productsHtml}
                        ${imageHtml}
                    </div>
                </div>
            `;
            
            typingIndicator.insertAdjacentHTML('beforebegin', aiHtml);
            chatBody.scrollTop = chatBody.scrollHeight;

        } catch (error) {
            console.error("Fetch Error:", error);
            typingIndicator.style.display = 'none';
            const errorHtml = `
                <div class="msg-row ai">
                    <div class="msg-bubble" style="color: #dc2626;">Network error occurred while contacting AI Shopper. Please try again.</div>
                </div>
            `;
            typingIndicator.insertAdjacentHTML('beforebegin', errorHtml);
            chatBody.scrollTop = chatBody.scrollHeight;
        }
    }
    
    function toggleSidebar() {
        const wrapper = document.querySelector('.aishopper-wrapper');
        wrapper.classList.toggle('sidebar-collapsed');
    }
    
    function searchCatalogFromChat() {
        const input = document.getElementById('chatInput');
        const query = input.value.trim();
        if (query) {
            window.location.href = 'index.php?q=product&search=' + encodeURIComponent(query);
        } else {
            showToast("Search", "Please type something to search the catalog.", "warning");
        }
    }

    // Add keypress handler for header search input
    document.addEventListener('DOMContentLoaded', () => {
        const headerSearch = document.getElementById('headerSearchInputVal');
        if (headerSearch) {
            headerSearch.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    headerSearch.closest('form').submit();
                }
            });
        }
    });
    
    // Initialize on load
    window.onload = init;
</script>
