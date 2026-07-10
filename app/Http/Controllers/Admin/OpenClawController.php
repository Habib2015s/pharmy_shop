<?php
// app/Http/Controllers/Admin/OpenClawController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
class OpenClawController extends Controller
{
    // بررسی وضعیت OpenClaw
    public function status()
    {
        $process = new Process(['openclaw', 'status']);

        $process->setTimeout(10);
        $process->run();

        return response()->json([
            'online' => $process->isSuccessful()
        ]);
    }
    // اجرای دستور
    public function run(Request $request)
    {
        $command = trim($request->input('command'));

        if ($command === '') {
            return response()->json([
                'error' => 'دستوری وارد نشده است.'
            ], 422);
        }

        $process = new Process([
            'openclaw',
            'agent',
            '--local',
            '--json',
            '--message',
            $command,
        ]);

        $process->setTimeout(180);
        $process->run();

        if (!$process->isSuccessful()) {
            return response()->json([
                'error' => $process->getErrorOutput() ?: $process->getOutput()
            ], 500);
        }

        return response()->json([
            'output' => $process->getOutput()
        ]);
    }}
