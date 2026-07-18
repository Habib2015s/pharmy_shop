{{-- resources/views/admin/openclaw/index.blade.php --}}
@extends('admin.layout.app')
@section('title', 'دستیار هوش مصنوعی')

@push('styles')
    <style>
        /* ══ رنگ‌ها ══ */
        :root {
            --ai-bg:      #0f1923;
            --ai-surface: #1a2535;
            --ai-border:  #253040;
            --ai-green:   #1a6b3c;
            --ai-green2:  #22c55e;
            --ai-blue:    #3b82f6;
            --ai-purple:  #8b5cf6;
            --ai-muted:   #64748b;
            --ai-text:    #e2e8f0;
            --ai-radius:  14px;
        }

        /* ══ wrapper ══ */
        .oc-wrap {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 1.25rem;
            height: calc(100vh - 160px);
            min-height: 560px;
        }

        /* ══ سایدبار ══ */
        .oc-sidebar {
            background: var(--ai-surface);
            border: 1px solid var(--ai-border);
            border-radius: var(--ai-radius);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .oc-sidebar-header {
            padding: 1rem 1.1rem .75rem;
            border-bottom: 1px solid var(--ai-border);
        }

        .oc-sidebar-header h6 {
            color: var(--ai-text);
            font-weight: 700;
            font-size: .82rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .oc-status {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .65rem 1.1rem;
            border-bottom: 1px solid var(--ai-border);
            font-size: .75rem;
            color: var(--ai-muted);
        }

        .status-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #ef4444;
            flex-shrink: 0;
            transition: background .3s;
        }

        .status-dot.online {
            background: var(--ai-green2);
            box-shadow: 0 0 6px rgba(34,197,94,.5);
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%,100% { box-shadow: 0 0 6px rgba(34,197,94,.4); }
            50%      { box-shadow: 0 0 12px rgba(34,197,94,.8); }
        }

        /* دسته‌بندی دستورات */
        .cmd-group {
            padding: .6rem 1.1rem .3rem;
            font-size: .65rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--ai-muted);
        }

        .quick-cmd {
            display: flex;
            align-items: center;
            gap: .6rem;
            width: 100%;
            padding: .55rem 1.1rem;
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: .8rem;
            font-family: inherit;
            text-align: right;
            cursor: pointer;
            transition: all .15s;
            border-right: 2px solid transparent;
        }

        .quick-cmd:hover {
            background: rgba(255,255,255,.04);
            color: var(--ai-text);
            border-right-color: var(--ai-green2);
            padding-right: 1.3rem;
        }

        .quick-cmd .cmd-icon {
            width: 26px; height: 26px;
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            font-size: .72rem;
            flex-shrink: 0;
        }

        .oc-sidebar-footer {
            margin-top: auto;
            padding: .85rem 1.1rem;
            border-top: 1px solid var(--ai-border);
        }

        .oc-sidebar-footer a {
            display: flex;
            align-items: center;
            gap: .5rem;
            color: var(--ai-muted);
            font-size: .78rem;
            text-decoration: none;
            padding: .4rem .6rem;
            border-radius: 8px;
            transition: all .15s;
        }

        .oc-sidebar-footer a:hover {
            background: rgba(255,255,255,.05);
            color: var(--ai-text);
        }

        /* ══ چت‌باکس ══ */
        .oc-chat {
            background: var(--ai-surface);
            border: 1px solid var(--ai-border);
            border-radius: var(--ai-radius);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .oc-chat-header {
            padding: .9rem 1.25rem;
            border-bottom: 1px solid var(--ai-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .oc-chat-title {
            display: flex;
            align-items: center;
            gap: .65rem;
        }

        .ai-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #1a6b3c, #22c55e);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: .95rem;
            color: #fff;
            flex-shrink: 0;
        }

        .oc-chat-title .title-text {
            font-weight: 700;
            color: var(--ai-text);
            font-size: .9rem;
            line-height: 1.2;
        }

        .oc-chat-title .title-sub {
            font-size: .7rem;
            color: var(--ai-muted);
        }

        .oc-chat-actions {
            display: flex;
            gap: .4rem;
        }

        .oc-action-btn {
            background: rgba(255,255,255,.05);
            border: 1px solid var(--ai-border);
            color: var(--ai-muted);
            border-radius: 8px;
            padding: .3rem .65rem;
            font-size: .75rem;
            cursor: pointer;
            transition: all .15s;
            font-family: inherit;
            display: flex;
            align-items: center;
            gap: .35rem;
        }

        .oc-action-btn:hover {
            background: rgba(255,255,255,.08);
            color: var(--ai-text);
        }

        /* ══ پیام‌ها ══ */
        .oc-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            scrollbar-width: thin;
            scrollbar-color: var(--ai-border) transparent;
        }

        .oc-messages::-webkit-scrollbar { width: 4px; }
        .oc-messages::-webkit-scrollbar-track { background: transparent; }
        .oc-messages::-webkit-scrollbar-thumb { background: var(--ai-border); border-radius: 2px; }

        /* Welcome */
        .oc-welcome {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--ai-muted);
        }

        .oc-welcome .welcome-icon {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, rgba(26,107,60,.3), rgba(34,197,94,.15));
            border: 1px solid rgba(34,197,94,.2);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem;
            margin: 0 auto 1rem;
        }

        .oc-welcome h6 {
            color: var(--ai-text);
            font-size: .95rem;
            margin-bottom: .4rem;
        }

        .oc-welcome p {
            font-size: .8rem;
            line-height: 1.7;
            max-width: 340px;
            margin: 0 auto;
        }

        /* سوژست‌های سریع */
        .oc-suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            justify-content: center;
            margin-top: 1.25rem;
        }

        .oc-suggest {
            background: rgba(255,255,255,.04);
            border: 1px solid var(--ai-border);
            border-radius: 20px;
            padding: .35rem .85rem;
            color: #94a3b8;
            font-size: .75rem;
            cursor: pointer;
            transition: all .15s;
            font-family: inherit;
        }

        .oc-suggest:hover {
            background: rgba(34,197,94,.08);
            border-color: rgba(34,197,94,.3);
            color: var(--ai-green2);
        }

        /* bubble کاربر */
        .msg-user {
            display: flex;
            justify-content: flex-end;
            gap: .6rem;
            align-items: flex-end;
            animation: msg-in .2s ease both;
        }

        /* bubble AI */
        .msg-ai {
            display: flex;
            justify-content: flex-start;
            gap: .6rem;
            align-items: flex-start;
            animation: msg-in .2s ease both;
        }

        @keyframes msg-in {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .msg-bubble {
            max-width: 72%;
            padding: .75rem 1rem;
            border-radius: 14px;
            font-size: .84rem;
            line-height: 1.65;
        }

        .msg-user .msg-bubble {
            background: linear-gradient(135deg, #1a4731, #1a6b3c);
            color: #d1fae5;
            border-bottom-left-radius: 4px;
        }

        .msg-ai .msg-bubble {
            background: #243040;
            border: 1px solid var(--ai-border);
            color: var(--ai-text);
            border-bottom-right-radius: 4px;
        }

        /* کد داخل پیام */
        .msg-bubble pre, .msg-bubble code {
            background: rgba(0,0,0,.3);
            border-radius: 6px;
            padding: .2rem .45rem;
            font-size: .78rem;
            color: #7dd3fc;
            font-family: 'Consolas', monospace;
            white-space: pre-wrap;
            word-break: break-all;
        }

        .msg-bubble pre {
            display: block;
            padding: .65rem .85rem;
            margin: .5rem 0 0;
            border: 1px solid rgba(255,255,255,.06);
            overflow-x: auto;
        }

        .msg-time {
            font-size: .65rem;
            color: var(--ai-muted);
            margin-top: .25rem;
            padding: 0 .2rem;
        }

        .msg-ai .msg-time { text-align: left; }
        .msg-user .msg-time { text-align: right; }

        /* typing indicator */
        .typing-indicator {
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .typing-dots {
            display: flex;
            gap: 4px;
            padding: .6rem .85rem;
            background: #243040;
            border: 1px solid var(--ai-border);
            border-radius: 14px;
            border-bottom-right-radius: 4px;
        }

        .typing-dots span {
            width: 6px; height: 6px;
            background: var(--ai-muted);
            border-radius: 50%;
            animation: typing-dot .9s ease-in-out infinite;
        }

        .typing-dots span:nth-child(2) { animation-delay: .15s; }
        .typing-dots span:nth-child(3) { animation-delay: .3s; }

        @keyframes typing-dot {
            0%,60%,100% { transform: translateY(0); opacity: .4; }
            30%          { transform: translateY(-5px); opacity: 1; }
        }

        /* ══ input area ══ */
        .oc-input-area {
            padding: .85rem 1.25rem;
            border-top: 1px solid var(--ai-border);
            background: rgba(0,0,0,.15);
        }

        .oc-input-wrap {
            display: flex;
            align-items: flex-end;
            gap: .65rem;
            background: #0f1923;
            border: 1.5px solid var(--ai-border);
            border-radius: 12px;
            padding: .55rem .65rem .55rem 1rem;
            transition: border-color .2s;
        }

        .oc-input-wrap:focus-within {
            border-color: rgba(34,197,94,.4);
            box-shadow: 0 0 0 3px rgba(34,197,94,.06);
        }

        .oc-input {
            flex: 1;
            background: transparent;
            border: none;
            color: var(--ai-text);
            font-size: .85rem;
            font-family: inherit;
            outline: none;
            resize: none;
            min-height: 24px;
            max-height: 120px;
            line-height: 1.5;
            padding: 0;
            scrollbar-width: none;
            direction: rtl;
        }

        .oc-input::placeholder { color: var(--ai-muted); }

        .oc-send-btn {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, #1a6b3c, #22c55e);
            border: none;
            border-radius: 9px;
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem;
            cursor: pointer;
            transition: all .2s;
            flex-shrink: 0;
        }

        .oc-send-btn:hover { transform: scale(1.08); filter: brightness(1.1); }
        .oc-send-btn:active { transform: scale(.95); }
        .oc-send-btn:disabled { opacity: .4; cursor: not-allowed; transform: none; }

        .input-hint {
            font-size: .68rem;
            color: var(--ai-muted);
            margin-top: .4rem;
            text-align: center;
        }

        /* ══ responsive ══ */
        @media (max-width: 991px) {
            .oc-wrap { grid-template-columns: 1fr; }
            .oc-sidebar { display: none; }
        }
    </style>
@endpush

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-robot text-success me-2"></i>دستیار هوش مصنوعی
            </h4>
            <small class="text-muted">OpenClaw — پروژه پخش دارو</small>
        </div>
        <a href="https://openclaw.ai" target="_blank"
           class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-external-link-alt me-1"></i> openclaw.ai
        </a>
    </div>

    <div class="oc-wrap">

        {{-- ══ سایدبار ══ --}}
        <aside class="oc-sidebar">

            {{-- هدر --}}
            <div class="oc-sidebar-header">
                <h6>
                    <div style="width:26px;height:26px;background:linear-gradient(135deg,#1a6b3c,#22c55e);
                            border-radius:7px;display:flex;align-items:center;justify-content:center;
                            font-size:.7rem;color:#fff">
                        <i class="fas fa-robot"></i>
                    </div>
                    OpenClaw Agent
                </h6>
            </div>

            {{-- وضعیت --}}
            <div class="oc-status">
                <div class="status-dot" id="statusDot"></div>
                <span id="statusText">در حال بررسی...</span>
            </div>

            {{-- دستورات سریع --}}
            <div style="flex:1;overflow-y:auto">
                <div class="cmd-group">📊 گزارش‌ها</div>

                @foreach([
                    ['fas fa-chart-bar','#3b82f6','#1e3a5f',   'آمار کلی امروز',      'show today statistics'],
                    ['fas fa-pills',    '#8b5cf6','#2e1b4e',   'داروهای کم‌موجودی',   'show low stock medicines'],
                    ['fas fa-clock',    '#f59e0b','#3d2a00',   'سفارش‌های در انتظار', 'show pending orders'],
                    ['fas fa-hospital', '#06b6d4','#073040',   'بدهی داروخانه‌ها',    'show pharmacy debts'],
                ] as $cmd)
                    <button class="quick-cmd" data-cmd="{{ $cmd[4] }}">
                        <div class="cmd-icon" style="background:{{ $cmd[2] }};color:{{ $cmd[1] }}">
                            <i class="{{ $cmd[0] }}"></i>
                        </div>
                        {{ $cmd[3] }}
                    </button>
                @endforeach

                <div class="cmd-group">⚙️ عملیات</div>

                @foreach([
                    ['fas fa-sync',    '#22c55e','#0f2d1a',    'اجرا migrate',         'php artisan migrate'],
                    ['fas fa-broom',   '#f97316','#3d1a00',    'پاک کردن کش',          'php artisan cache:clear'],
                    ['fas fa-database','#a78bfa','#2a1654',    'وضعیت دیتابیس',        'SELECT COUNT(*) as medicines FROM medicines'],
                    ['fas fa-file-alt','#94a3b8','#1e2a38',    'لاگ‌های اخیر',         'php artisan about'],
                ] as $cmd)
                    <button class="quick-cmd" data-cmd="{{ $cmd[4] }}">
                        <div class="cmd-icon" style="background:{{ $cmd[2] }};color:{{ $cmd[1] }}">
                            <i class="{{ $cmd[0] }}"></i>
                        </div>
                        {{ $cmd[3] }}
                    </button>
                @endforeach

                <div class="cmd-group">🔧 لاراول</div>

                @foreach([
                    ['fas fa-route',   '#34d399','#0a2d1e',    'لیست route‌ها',         'php artisan route:list'],
                    ['fas fa-shield',  '#60a5fa','#0f2040',    'پاک کردن config',       'php artisan config:clear'],
                    ['fas fa-magic',   '#c084fc','#280d42',    'بهینه‌سازی',            'php artisan optimize:clear'],
                ] as $cmd)
                    <button class="quick-cmd" data-cmd="{{ $cmd[4] }}">
                        <div class="cmd-icon" style="background:{{ $cmd[2] }};color:{{ $cmd[1] }}">
                            <i class="{{ $cmd[0] }}"></i>
                        </div>
                        {{ $cmd[3] }}
                    </button>
                @endforeach
            </div>

            {{-- footer سایدبار --}}
            <div class="oc-sidebar-footer">
                <a href="https://t.me/YOUR_BOT" target="_blank">
                    <i class="fab fa-telegram" style="color:#0088cc"></i>
                    باز کردن در Telegram
                </a>
                <a href="#" onclick="clearChat()">
                    <i class="fas fa-trash" style="color:#ef4444"></i>
                    پاک کردن مکالمه
                </a>
            </div>
        </aside>

        {{-- ══ چت ══ --}}
        <div class="oc-chat">

            {{-- هدر چت --}}
            <div class="oc-chat-header">
                <div class="oc-chat-title">
                    <div class="ai-avatar"><i class="fas fa-robot"></i></div>
                    <div>
                        <div class="title-text">OpenClaw Assistant</div>
                        <div class="title-sub" id="headerStatus">در حال اتصال...</div>
                    </div>
                </div>
                <div class="oc-chat-actions">
                    <button class="oc-action-btn" onclick="clearChat()">
                        <i class="fas fa-trash"></i> پاک کردن
                    </button>
                    <button class="oc-action-btn" onclick="checkStatus()">
                        <i class="fas fa-sync"></i> بررسی اتصال
                    </button>
                </div>
            </div>

            {{-- پیام‌ها --}}
            <div class="oc-messages" id="messages">

                {{-- Welcome --}}
                <div class="oc-welcome" id="welcomeBlock">
                    <div class="welcome-icon">🤖</div>
                    <h6>سلام! دستیار OpenClaw آماده‌ست</h6>
                    <p>
                        می‌تونی دستوراتی مثل گزارش‌گیری از دیتابیس،
                        اجرای artisan، یا هر سوالی درباره پروژه بپرسی.
                    </p>
                    <div class="oc-suggestions">
                        <button class="oc-suggest" data-cmd="show today statistics">📊 آمار امروز</button>
                        <button class="oc-suggest" data-cmd="show low stock medicines">💊 کم‌موجودی</button>
                        <button class="oc-suggest" data-cmd="php artisan migrate:status">🗃️ وضعیت migration</button>
                        <button class="oc-suggest" data-cmd="SELECT COUNT(*) as total FROM orders">📋 تعداد سفارش‌ها</button>
                    </div>
                </div>

            </div>

            {{-- input --}}
            <div class="oc-input-area">
                <div class="oc-input-wrap">
                <textarea class="oc-input" id="msgInput" rows="1"
                          placeholder="دستور یا سوال خود را بنویسید..."></textarea>
                    <button class="oc-send-btn" id="sendBtn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                <div class="input-hint">
                    Enter برای ارسال · Shift+Enter برای خط جدید ·
                    <span style="color:#22c55e">php artisan</span>، SQL و سوال‌های آزاد پشتیبانی می‌شن
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const messagesEl = document.getElementById('messages');
        const msgInput   = document.getElementById('msgInput');
        const sendBtn    = document.getElementById('sendBtn');
        const welcomeEl  = document.getElementById('welcomeBlock');
        let   isLoading  = false;

        /* ══ تابع‌های کمکی ══ */
        function now() {
            return new Date().toLocaleTimeString('fa-IR', { hour: '2-digit', minute: '2-digit' });
        }

        function scrollBottom() {
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        function hideWelcome() {
            if (welcomeEl) welcomeEl.style.display = 'none';
        }

        /* ══ اضافه کردن پیام کاربر ══ */
        function addUserMsg(text) {
            hideWelcome();
            const div = document.createElement('div');
            div.className = 'msg-user';
            div.innerHTML = `
        <div>
            <div class="msg-bubble">${escapeHtml(text)}</div>
            <div class="msg-time">${now()}</div>
        </div>
    `;
            messagesEl.appendChild(div);
            scrollBottom();
        }

        /* ══ اضافه کردن پیام AI ══ */
        function addAiMsg(text, isError = false) {
            // کد را در pre بپیچ
            const formatted = formatText(text, isError);
            const div = document.createElement('div');
            div.className = 'msg-ai';
            div.innerHTML = `
        <div class="ai-avatar" style="flex-shrink:0;width:28px;height:28px;font-size:.7rem;border-radius:8px">
            <i class="fas fa-robot"></i>
        </div>
        <div>
            <div class="msg-bubble" style="${isError ? 'border-color:rgba(239,68,68,.3);background:rgba(239,68,68,.06)' : ''}">
                ${formatted}
            </div>
            <div class="msg-time">${now()}</div>
        </div>
    `;
            messagesEl.appendChild(div);
            scrollBottom();
        }

        /* ══ typing indicator ══ */
        function showTyping() {
            const div = document.createElement('div');
            div.className = 'msg-ai';
            div.id = 'typingMsg';
            div.innerHTML = `
        <div class="ai-avatar" style="flex-shrink:0;width:28px;height:28px;font-size:.7rem;border-radius:8px">
            <i class="fas fa-robot"></i>
        </div>
        <div class="typing-indicator">
            <div class="typing-dots">
                <span></span><span></span><span></span>
            </div>
        </div>
    `;
            messagesEl.appendChild(div);
            scrollBottom();
        }

        function hideTyping() {
            const t = document.getElementById('typingMsg');
            if (t) t.remove();
        }

        /* ══ فرمت متن خروجی ══ */
        function formatText(text, isError) {
            if (isError) {
                return `<span style="color:#fca5a5"><i class="fas fa-exclamation-circle me-1"></i>${escapeHtml(text)}</span>`;
            }
            // اگه چند خط داره، code block نشون بده
            const lines = text.trim().split('\n');
            if (lines.length > 2 || text.includes('|') || text.includes('=')) {
                return `<pre>${escapeHtml(text)}</pre>`;
            }
            return escapeHtml(text).replace(/`([^`]+)`/g, '<code>$1</code>');
        }

        function escapeHtml(s) {
            return String(s)
                .replace(/&/g,'&amp;')
                .replace(/</g,'&lt;')
                .replace(/>/g,'&gt;');
        }

        /* ══ ارسال دستور ══ */
        async function sendCommand(cmd) {
            cmd = cmd.trim();
            if (!cmd || isLoading) return;

            isLoading = true;
            sendBtn.disabled = true;
            msgInput.value = '';
            msgInput.style.height = 'auto';

            addUserMsg(cmd);
            showTyping();

            try {
                const res = await fetch('{{ route("admin.openclaw.run") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ command: cmd })
                });

                const data = await res.json();
                hideTyping();

                if (data.error) {
                    addAiMsg(data.error, true);
                } else {
                    addAiMsg(data.output || '✅ دستور با موفقیت اجرا شد.');
                }

            } catch (e) {
                hideTyping();
                addAiMsg('خطا در اتصال به سرور.', true);
            }

            isLoading = false;
            sendBtn.disabled = false;
            msgInput.focus();
        }

        /* ══ پاک کردن چت ══ */
        function clearChat() {
            messagesEl.innerHTML = '';
            if (welcomeEl) {
                messagesEl.appendChild(welcomeEl);
                welcomeEl.style.display = '';
            }
        }

        /* ══ وضعیت اتصال ══ */
        async function checkStatus() {
            document.getElementById('headerStatus').textContent = 'در حال بررسی...';
            try {
                const res  = await fetch('{{ route("admin.openclaw.status") }}');
                const data = await res.json();
                const dot  = document.getElementById('statusDot');
                const txt  = document.getElementById('statusText');
                const hdr  = document.getElementById('headerStatus');

                if (data.online) {
                    dot.classList.add('online');
                    txt.textContent = `آنلاین — v${data.version || '?'}`;
                    hdr.textContent = 'آنلاین و آماده';
                } else {
                    dot.classList.remove('online');
                    txt.textContent = 'آفلاین — openclaw را اجرا کنید';
                    hdr.textContent = 'آفلاین';
                }
            } catch {
                document.getElementById('headerStatus').textContent = 'خطای اتصال';
            }
        }

        /* ══ event listeners ══ */
        sendBtn.addEventListener('click', () => sendCommand(msgInput.value));

        msgInput.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendCommand(msgInput.value);
            }
        });

        // auto-resize textarea
        msgInput.addEventListener('input', () => {
            msgInput.style.height = 'auto';
            msgInput.style.height = Math.min(msgInput.scrollHeight, 120) + 'px';
        });

        // دستورات سریع سایدبار
        document.querySelectorAll('.quick-cmd').forEach(btn => {
            btn.addEventListener('click', () => sendCommand(btn.dataset.cmd));
        });

        // سوژست‌های welcome
        document.querySelectorAll('.oc-suggest').forEach(btn => {
            btn.addEventListener('click', () => sendCommand(btn.dataset.cmd));
        });

        // بررسی اتصال هنگام بارگذاری
        checkStatus();
    </script>
@endpush
