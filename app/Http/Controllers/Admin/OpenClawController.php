<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Artisan};
use Symfony\Component\Process\Process;

class OpenClawController extends Controller
{
    // ══════════════════════════════════════════════════════
    // نقشه دستورات فارسی/انگلیسی → SQL یا Artisan
    // ══════════════════════════════════════════════════════
    private array $commandMap = [

        // ─── داروها ──────────────────────────────────────
        'show low stock medicines'    => ['type'=>'sql', 'query'=>"SELECT id, name, generic_name, stock, min_stock, unit FROM medicines WHERE stock <= min_stock AND is_active=1 ORDER BY stock ASC"],
        'داروهای کم‌موجودی'           => ['type'=>'sql', 'query'=>"SELECT id, name, generic_name, stock, min_stock, unit FROM medicines WHERE stock <= min_stock AND is_active=1 ORDER BY stock ASC"],
        'داروهای کم موجودی'           => ['type'=>'sql', 'query'=>"SELECT id, name, generic_name, stock, min_stock, unit FROM medicines WHERE stock <= min_stock AND is_active=1 ORDER BY stock ASC"],
        'low stock'                   => ['type'=>'sql', 'query'=>"SELECT id, name, generic_name, stock, min_stock FROM medicines WHERE stock <= min_stock AND is_active=1 ORDER BY stock ASC"],
        'show medicines'              => ['type'=>'sql', 'query'=>"SELECT id, name, generic_name, sale_price, stock, unit FROM medicines WHERE is_active=1 ORDER BY id DESC LIMIT 20"],
        'all medicines'               => ['type'=>'sql', 'query'=>"SELECT id, name, generic_name, sale_price, stock FROM medicines ORDER BY id DESC LIMIT 20"],
        'expired medicines'           => ['type'=>'sql', 'query'=>"SELECT id, name, generic_name, expiry_date, stock FROM medicines WHERE expiry_date < NOW() AND is_active=1"],
        'داروهای منقضی'               => ['type'=>'sql', 'query'=>"SELECT id, name, generic_name, expiry_date, stock FROM medicines WHERE expiry_date < NOW() AND is_active=1"],

        // ─── سفارش‌ها ─────────────────────────────────────
        'show pending orders'         => ['type'=>'sql', 'query'=>"SELECT o.id, o.order_number, p.name as pharmacy, o.final_amount, o.created_at FROM orders o JOIN pharmacies p ON o.pharmacy_id=p.id WHERE o.status='pending' ORDER BY o.created_at DESC"],
        'سفارش‌های در انتظار'          => ['type'=>'sql', 'query'=>"SELECT o.id, o.order_number, p.name as pharmacy, o.final_amount, o.created_at FROM orders o JOIN pharmacies p ON o.pharmacy_id=p.id WHERE o.status='pending' ORDER BY o.created_at DESC"],
        'pending orders'              => ['type'=>'sql', 'query'=>"SELECT o.id, o.order_number, p.name as pharmacy, o.final_amount, o.created_at FROM orders o JOIN pharmacies p ON o.pharmacy_id=p.id WHERE o.status='pending' ORDER BY o.created_at DESC"],
        'show orders'                 => ['type'=>'sql', 'query'=>"SELECT o.id, o.order_number, p.name as pharmacy, o.status, o.final_amount, o.created_at FROM orders o JOIN pharmacies p ON o.pharmacy_id=p.id ORDER BY o.created_at DESC LIMIT 15"],
        'show today orders'           => ['type'=>'sql', 'query'=>"SELECT o.id, o.order_number, p.name as pharmacy, o.status, o.final_amount FROM orders o JOIN pharmacies p ON o.pharmacy_id=p.id WHERE DATE(o.created_at)=CURDATE() ORDER BY o.created_at DESC"],
        'سفارش‌های امروز'              => ['type'=>'sql', 'query'=>"SELECT o.id, o.order_number, p.name as pharmacy, o.status, o.final_amount FROM orders o JOIN pharmacies p ON o.pharmacy_id=p.id WHERE DATE(o.created_at)=CURDATE() ORDER BY o.created_at DESC"],
        'today orders'                => ['type'=>'sql', 'query'=>"SELECT o.id, o.order_number, p.name as pharmacy, o.status, o.final_amount FROM orders o JOIN pharmacies p ON o.pharmacy_id=p.id WHERE DATE(o.created_at)=CURDATE() ORDER BY o.created_at DESC"],
        'delivered orders'            => ['type'=>'sql', 'query'=>"SELECT o.id, o.order_number, p.name as pharmacy, o.final_amount, o.delivered_at FROM orders o JOIN pharmacies p ON o.pharmacy_id=p.id WHERE o.status='delivered' ORDER BY o.delivered_at DESC LIMIT 20"],

        // ─── آمار ─────────────────────────────────────────
        'show today statistics'       => ['type'=>'stats', 'key'=>'today'],
        'آمار امروز'                   => ['type'=>'stats', 'key'=>'today'],
        'today statistics'            => ['type'=>'stats', 'key'=>'today'],
        'statistics'                  => ['type'=>'stats', 'key'=>'today'],
        'show statistics'             => ['type'=>'stats', 'key'=>'today'],
        'آمار کلی'                    => ['type'=>'stats', 'key'=>'general'],
        'general statistics'          => ['type'=>'stats', 'key'=>'general'],
        'monthly statistics'          => ['type'=>'stats', 'key'=>'monthly'],
        'آمار ماهانه'                  => ['type'=>'stats', 'key'=>'monthly'],

        // ─── داروخانه‌ها ───────────────────────────────────
        'show pharmacies'             => ['type'=>'sql', 'query'=>"SELECT id, name, city, phone, current_balance, credit_limit FROM pharmacies WHERE is_active=1 ORDER BY current_balance DESC"],
        'داروخانه‌ها'                  => ['type'=>'sql', 'query'=>"SELECT id, name, city, phone, current_balance, credit_limit FROM pharmacies WHERE is_active=1 ORDER BY current_balance DESC"],
        'show pharmacy debts'         => ['type'=>'sql', 'query'=>"SELECT name, city, current_balance, credit_limit, (credit_limit - current_balance) as available_credit FROM pharmacies WHERE current_balance > 0 AND is_active=1 ORDER BY current_balance DESC"],
        'بدهی داروخانه‌ها'             => ['type'=>'sql', 'query'=>"SELECT name, city, current_balance, credit_limit FROM pharmacies WHERE current_balance > 0 AND is_active=1 ORDER BY current_balance DESC"],
        'pharmacy debts'              => ['type'=>'sql', 'query'=>"SELECT name, city, current_balance, credit_limit FROM pharmacies WHERE current_balance > 0 ORDER BY current_balance DESC"],

        // ─── فاکتورها ─────────────────────────────────────
        'show unpaid invoices'        => ['type'=>'sql', 'query'=>"SELECT i.invoice_number, p.name as pharmacy, i.amount, i.due_date, i.status FROM invoices i JOIN pharmacies p ON i.pharmacy_id=p.id WHERE i.status != 'paid' ORDER BY i.due_date ASC"],
        'فاکتورهای پرداخت‌نشده'        => ['type'=>'sql', 'query'=>"SELECT i.invoice_number, p.name as pharmacy, i.amount, i.due_date FROM invoices i JOIN pharmacies p ON i.pharmacy_id=p.id WHERE i.status != 'paid' ORDER BY i.due_date ASC"],
        'overdue invoices'            => ['type'=>'sql', 'query'=>"SELECT i.invoice_number, p.name as pharmacy, i.amount, i.due_date FROM invoices i JOIN pharmacies p ON i.pharmacy_id=p.id WHERE i.status != 'paid' AND i.due_date < NOW() ORDER BY i.due_date ASC"],

        // ─── حرکت انبار ───────────────────────────────────
        'show stock movements'        => ['type'=>'sql', 'query'=>"SELECT sm.id, m.name as medicine, sm.type, sm.quantity, sm.stock_before, sm.stock_after, sm.created_at FROM stock_movements sm JOIN medicines m ON sm.medicine_id=m.id ORDER BY sm.created_at DESC LIMIT 20"],
        'حرکات انبار'                  => ['type'=>'sql', 'query'=>"SELECT sm.id, m.name as medicine, sm.type, sm.quantity, sm.stock_before, sm.stock_after, sm.created_at FROM stock_movements sm JOIN medicines m ON sm.medicine_id=m.id ORDER BY sm.created_at DESC LIMIT 20"],

        // ─── Artisan ──────────────────────────────────────
        'php artisan migrate'         => ['type'=>'artisan', 'cmd'=>'migrate'],
        'php artisan migrate:status'  => ['type'=>'artisan', 'cmd'=>'migrate:status'],
        'php artisan cache:clear'     => ['type'=>'artisan', 'cmd'=>'cache:clear'],
        'php artisan config:clear'    => ['type'=>'artisan', 'cmd'=>'config:clear'],
        'php artisan view:clear'      => ['type'=>'artisan', 'cmd'=>'view:clear'],
        'php artisan optimize:clear'  => ['type'=>'artisan', 'cmd'=>'optimize:clear'],
        'php artisan route:list'      => ['type'=>'artisan', 'cmd'=>'route:list'],
        'php artisan about'           => ['type'=>'artisan', 'cmd'=>'about'],
        'پاک کردن کش'                 => ['type'=>'artisan', 'cmd'=>'cache:clear'],
        'بهینه‌سازی'                   => ['type'=>'artisan', 'cmd'=>'optimize:clear'],
        'لیست route‌ها'                => ['type'=>'artisan', 'cmd'=>'route:list'],
        'وضعیت migration'              => ['type'=>'artisan', 'cmd'=>'migrate:status'],
        'اجرا migrate'                 => ['type'=>'artisan', 'cmd'=>'migrate'],
        'پاک کردن config'              => ['type'=>'artisan', 'cmd'=>'config:clear'],
    ];

    // ══════════════════════════════════════════════════════
    public function index()
    {
        return view('admin.openclaw.index');
    }

    // ══════════════════════════════════════════════════════
    public function status()
    {
        try {
            $proc = $this->makeProcess(['--version'], 8);
            $proc->run();

            return response()->json([
                'online'  => $proc->isSuccessful(),
                'version' => trim($proc->getOutput()),
            ]);
        } catch (\Exception $e) {
            return response()->json(['online' => false, 'error' => $e->getMessage()]);
        }
    }

    // ══════════════════════════════════════════════════════
    public function run(Request $request)
    {
        $raw = trim($request->input('command', ''));

        if ($raw === '') {
            return response()->json(['error' => 'دستوری وارد نشده.'], 422);
        }

        $lower = mb_strtolower($raw);

        // ─── ۱. جستجو در نقشه دستورات ────────────────────
        foreach ($this->commandMap as $key => $action) {
            if ($lower === mb_strtolower($key)) {
                return $this->execute($action, $raw);
            }
        }

        // ─── ۲. جستجوی جزئی (اگه دقیق پیدا نشد) ─────────
        foreach ($this->commandMap as $key => $action) {
            if (str_contains($lower, mb_strtolower($key))) {
                return $this->execute($action, $raw);
            }
        }

        // ─── ۳. SQL خام ───────────────────────────────────
        if (preg_match('/^\s*(select|describe|explain|show\s+tables|show\s+columns|show\s+databases)\s+/i', $raw)) {
            return $this->runSql($raw);
        }

        // ─── ۴. Artisan خام ───────────────────────────────
        if (preg_match('/^php artisan\s+(.+)$/i', $raw, $m)) {
            return $this->runArtisan(trim($m[1]));
        }

        // ─── ۵. OpenClaw agent ────────────────────────────
        return $this->runOpenClaw($raw);
    }

    // ══════════════════════════════════════════════════════
    private function execute(array $action, string $original): \Illuminate\Http\JsonResponse
    {
        return match($action['type']) {
            'sql'     => $this->runSql($action['query']),
            'artisan' => $this->runArtisan($action['cmd']),
            'stats'   => $this->runStats($action['key']),
            default   => response()->json(['error' => 'نوع دستور ناشناخته.'], 400),
        };
    }

    // ══════════════════════════════════════════════════════
    private function runSql(string $sql): \Illuminate\Http\JsonResponse
    {
        try {
            $rows = DB::select($sql);

            if (empty($rows)) {
                return response()->json(['output' => '✅ نتیجه‌ای یافت نشد.']);
            }

            $keys   = array_keys((array) $rows[0]);
            $widths = array_fill_keys($keys, 0);

            // محاسبه عرض ستون‌ها
            foreach ($keys as $k) {
                $widths[$k] = mb_strlen($k);
            }
            foreach ($rows as $row) {
                foreach ((array)$row as $k => $v) {
                    $widths[$k] = max($widths[$k], mb_strlen((string)$v));
                }
            }

            // ساخت جدول متنی
            $sep  = '+' . implode('+', array_map(fn($w) => str_repeat('-', $w+2), $widths)) . '+';
            $head = '|' . implode('|', array_map(fn($k) => ' ' . str_pad($k, $widths[$k], ' ', STR_PAD_LEFT) . ' ', $keys)) . '|';
            $lines = [$sep, $head, $sep];

            foreach ($rows as $row) {
                $line = '|';
                foreach ((array)$row as $k => $v) {
                    $v = (string)$v;
                    $line .= ' ' . str_pad($v, $widths[$k], ' ', STR_PAD_LEFT) . ' |';
                }
                $lines[] = $line;
            }
            $lines[] = $sep;
            $lines[] = '✅ ' . count($rows) . ' ردیف یافت شد.';

            return response()->json(['output' => implode("\n", $lines)]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'خطای SQL: ' . $e->getMessage()], 500);
        }
    }

    // ══════════════════════════════════════════════════════
    private function runArtisan(string $cmd): \Illuminate\Http\JsonResponse
    {
        $allowed = [
            'migrate','migrate:status','cache:clear','config:clear',
            'view:clear','optimize:clear','route:list','about',
            'queue:work','storage:link','inspire',
        ];

        $base = explode(' ', $cmd)[0] . (str_contains($cmd,' ') ? ':'.explode(':',explode(' ',$cmd)[0])[1]??'' : '');
        $base = explode(' ', $cmd)[0];

        if (!in_array($base, $allowed)) {
            return response()->json(['error' => "دستور «{$base}» مجاز نیست.\n\nدستورات مجاز:\n" . implode(', ', $allowed)], 403);
        }

        try {
            Artisan::call($cmd);
            $out = Artisan::output();
            return response()->json([
                'output' => $out ?: "✅ دستور «php artisan {$cmd}» با موفقیت اجرا شد."
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ══════════════════════════════════════════════════════
    private function runStats(string $key): \Illuminate\Http\JsonResponse
    {
        try {
            if ($key === 'today') {
                $data = [
                    'سفارش‌های امروز'       => DB::selectOne("SELECT COUNT(*) as c FROM orders WHERE DATE(created_at)=CURDATE()")->c,
                    'تحویل‌های امروز'        => DB::selectOne("SELECT COUNT(*) as c FROM orders WHERE DATE(delivered_at)=CURDATE() AND status='delivered'")->c,
                    'سفارش در انتظار'        => DB::selectOne("SELECT COUNT(*) as c FROM orders WHERE status='pending'")->c,
                    'فروش امروز (تومان)'     => number_format(DB::selectOne("SELECT COALESCE(SUM(final_amount),0) as s FROM orders WHERE DATE(delivered_at)=CURDATE() AND status='delivered'")->s),
                    'داروهای کم‌موجودی'      => DB::selectOne("SELECT COUNT(*) as c FROM medicines WHERE stock<=min_stock AND is_active=1")->c,
                    'کل داروخانه‌های فعال'   => DB::selectOne("SELECT COUNT(*) as c FROM pharmacies WHERE is_active=1")->c,
                ];
            } elseif ($key === 'monthly') {
                $data = [
                    'سفارش این ماه'          => DB::selectOne("SELECT COUNT(*) as c FROM orders WHERE MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())")->c,
                    'درآمد این ماه (تومان)'  => number_format(DB::selectOne("SELECT COALESCE(SUM(final_amount),0) as s FROM orders WHERE status='delivered' AND MONTH(delivered_at)=MONTH(NOW()) AND YEAR(delivered_at)=YEAR(NOW())")->s),
                    'میانگین سفارش (تومان)'  => number_format(DB::selectOne("SELECT COALESCE(AVG(final_amount),0) as a FROM orders WHERE MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())")->a),
                ];
            } else {
                $data = [
                    'کل داروها'              => DB::selectOne("SELECT COUNT(*) as c FROM medicines WHERE is_active=1")->c,
                    'کل سفارش‌ها'            => DB::selectOne("SELECT COUNT(*) as c FROM orders")->c,
                    'کل داروخانه‌ها'          => DB::selectOne("SELECT COUNT(*) as c FROM pharmacies")->c,
                    'کل درآمد (تومان)'       => number_format(DB::selectOne("SELECT COALESCE(SUM(final_amount),0) as s FROM orders WHERE status='delivered'")->s),
                    'مجموع بدهی‌ها (تومان)'  => number_format(DB::selectOne("SELECT COALESCE(SUM(current_balance),0) as s FROM pharmacies")->s),
                ];
            }

            $lines = [];
            foreach ($data as $label => $value) {
                $lines[] = sprintf("  %-30s %s", $label . ':', $value);
            }

            return response()->json([
                'output' => "📊 آمار " . ($key==='today'?'امروز':($key==='monthly'?'ماهانه':'کلی')) . "\n" .
                    str_repeat('─', 46) . "\n" .
                    implode("\n", $lines)
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ══════════════════════════════════════════════════════
    private function runOpenClaw(string $msg): \Illuminate\Http\JsonResponse
    {
        try {
            $proc = $this->makeProcess(['run', '--message', $msg, '--format', 'text'], 120);
            $proc->run();

            $out = $proc->getOutput();
            $err = $proc->getErrorOutput();

            if (!$proc->isSuccessful() && empty($out)) {
                // OpenClaw آفلاینه — یه پاسخ راهنما بده
                return response()->json([
                    'output' => "⚠️ OpenClaw آفلاین است.\n\n" .
                        "دستوراتی که بدون openclaw کار می‌کنن:\n" .
                        "• show low stock medicines\n" .
                        "• show pending orders\n" .
                        "• show today statistics\n" .
                        "• show pharmacy debts\n" .
                        "• php artisan cache:clear\n" .
                        "• SELECT ... FROM ...\n\n" .
                        "برای فعال‌سازی openclaw تایپ کنید:\n> openclaw start"
                ]);
            }

            return response()->json(['output' => $out ?: $err]);

        } catch (\Exception $e) {
            return response()->json([
                'output' => "⚠️ openclaw در دسترس نیست.\n\nدستورات داخلی:\n• show low stock medicines\n• show pending orders\n• show today statistics\n• php artisan cache:clear"
            ]);
        }
    }

    // ══════════════════════════════════════════════════════
    private function openclawPath(): string
    {
        $paths = [
            'C:\\Users\\red pc\\AppData\\Roaming\\npm\\openclaw.cmd',
            'C:\\Users\\red pc\\AppData\\Local\\npm\\openclaw.cmd',
        ];
        foreach ($paths as $p) {
            if (file_exists($p)) return $p;
        }
        return 'openclaw';
    }

    private function makeProcess(array $args, int $timeout = 20): Process
    {
        $proc = new Process(array_merge([$this->openclawPath()], $args));
        $proc->setEnv([
            'PATH'       => 'C:\\Program Files\\nodejs;C:\\Users\\red pc\\AppData\\Roaming\\npm;' . (getenv('PATH') ?: ''),
            'APPDATA'    => 'C:\\Users\\red pc\\AppData\\Roaming',
            'USERPROFILE'=> 'C:\\Users\\red pc',
            'HOME'       => 'C:\\Users\\red pc',
        ]);
        $proc->setTimeout($timeout);
        return $proc;
    }
}
