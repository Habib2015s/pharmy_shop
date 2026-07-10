@extends('admin.layout.app')
@section('title', 'دستیار هوش مصنوعی')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="fas fa-robot text-success me-2"></i>دستیار OpenClaw
        </h4>
    </div>

    <div class="row g-4">
        {{-- وضعیت اتصال --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div id="statusDot" class="rounded-circle"
                         style="width:12px;height:12px;background:#dc3545"></div>
                    <span id="statusText" class="fw-600">در حال بررسی اتصال...</span>
                    <a href="https://t.me/YOUR_BOT_USERNAME" target="_blank"
                       class="btn btn-success btn-sm ms-auto">
                        <i class="fab fa-telegram me-1"></i> باز کردن در Telegram
                    </a>
                </div>
            </div>
        </div>

        {{-- دستورات سریع --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header fw-700">
                    <i class="fas fa-bolt text-warning me-2"></i>دستورات سریع
                </div>
                <div class="card-body p-0">
                    @foreach([
                        ['fas fa-database','بررسی دیتابیس','show me database status'],
                        ['fas fa-exclamation-triangle','داروهای کم‌موجودی','show low stock medicines'],
                        ['fas fa-chart-line','آمار امروز','show today statistics'],
                        ['fas fa-sync','اجرای migration','run php artisan migrate'],
                        ['fas fa-broom','پاک کردن کش','run php artisan cache:clear'],
                        ['fas fa-file-alt','آخرین لاگ‌ها','show last 20 log lines'],
                    ] as $cmd)
                        <button class="w-100 text-start p-3 border-0 bg-transparent border-bottom quick-cmd"
                                data-cmd="{{ $cmd[2] }}"
                                style="transition:.15s;cursor:pointer"
                                onmouseover="this.style.background='#f0fdf4'"
                                onmouseout="this.style.background='transparent'">
                            <i class="{{ $cmd[0] }} text-success me-2"></i>
                            {{ $cmd[1] }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ترمینال / چت --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header fw-700 d-flex justify-content-between">
                    <span><i class="fas fa-terminal text-success me-2"></i>ترمینال دستیار</span>
                    <button class="btn btn-sm btn-outline-danger" id="clearBtn">
                        <i class="fas fa-trash me-1"></i>پاک کردن
                    </button>
                </div>
                <div class="card-body p-0 d-flex flex-column" style="height:420px">
                    {{-- خروجی --}}
                    <div id="output"
                         style="flex:1;overflow-y:auto;padding:1rem;
                            background:#0d1117;color:#c9d1d9;
                            font-family:monospace;font-size:.82rem;
                            border-radius:0">
                        <div class="text-success">
                            # OpenClaw Terminal — پروژه پخش دارو
                        </div>
                        <div class="text-muted">
                            # دستور بنویس یا از دستورات سریع استفاده کن
                        </div>
                    </div>

                    {{-- ورودی --}}
                    <div class="d-flex border-top" style="background:#161b22">
                    <span style="color:#58a6ff;padding:.75rem .5rem .75rem 1rem;font-family:monospace">
                        ❯
                    </span>
                        <input type="text" id="cmdInput"
                               class="border-0 flex-grow-1 text-white bg-transparent"
                               style="outline:none;font-family:monospace;font-size:.85rem;padding:.7rem .5rem"
                               placeholder="دستور بنویس... (مثال: show orders today)">
                        <button class="btn btn-sm m-2 px-3"
                                style="background:#238636;color:#fff;border:none;border-radius:6px"
                                id="sendBtn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const output   = document.getElementById('output');
        const cmdInput = document.getElementById('cmdInput');
        const sendBtn  = document.getElementById('sendBtn');

        function addLine(text, color = '#c9d1d9') {
            const div = document.createElement('div');
            div.style.color = color;
            div.style.marginTop = '4px';
            div.innerText = text;
            output.appendChild(div);
            output.scrollTop = output.scrollHeight;
        }

        async function sendCommand(cmd) {
            if (!cmd.trim()) return;
            addLine('❯ ' + cmd, '#58a6ff');
            addLine('در حال پردازش...', '#8b949e');

            try {
                const res = await fetch('/admin/openclaw/run', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ command: cmd })
                });
                const data = await res.json();
                // حذف خط "در حال پردازش"
                output.removeChild(output.lastChild);
                addLine(data.output || data.error, data.error ? '#f85149' : '#3fb950');
            } catch (e) {
                output.removeChild(output.lastChild);
                addLine('خطا در اتصال به OpenClaw', '#f85149');
            }
            cmdInput.value = '';
        }

        sendBtn.addEventListener('click', () => sendCommand(cmdInput.value));
        cmdInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') sendCommand(cmdInput.value);
        });

        document.querySelectorAll('.quick-cmd').forEach(btn => {
            btn.addEventListener('click', () => sendCommand(btn.dataset.cmd));
        });

        document.getElementById('clearBtn').addEventListener('click', () => {
            output.innerHTML = '<div style="color:#58a6ff"># ترمینال پاک شد</div>';
        });

        // بررسی وضعیت اتصال
        fetch('/admin/openclaw/status')
            .then(r => r.json())
            .then(d => {
                document.getElementById('statusDot').style.background = d.online ? '#238636' : '#dc3545';
                document.getElementById('statusText').textContent = d.online
                    ? 'OpenClaw آنلاین است'
                    : 'OpenClaw آفلاین — آن را اجرا کنید';
            }).catch(() => {
            document.getElementById('statusText').textContent = 'اتصال برقرار نشد';
        });
    </script>
@endpush
