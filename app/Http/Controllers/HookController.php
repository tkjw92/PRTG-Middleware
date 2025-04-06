<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Jobs\InsertPRTGJobs;

class HookController extends Controller
{
    public function timeToMilliseconds($time) {
        list($hms, $micro) = explode('.', $time);
        list($hours, $minutes, $seconds) = explode(':', $hms);

        $totalMs = (
            ($hours * 3600) +
            ($minutes * 60) +
            $seconds
        ) * 1000 + ($micro / 1000);

        return ltrim(number_format($totalMs, 3, '.', ''), '0');
    }

    public function store(Request $request) {
        $uniq_id = $request->uniq_id;
        $data = $request->_id;
        $data = explode(';', $data);
        try {
            $latency = $this->timeToMilliseconds(explode('=', $data[4])[1]) ?? -1;
        } catch (\Exception $e) {
            $latency = -1;
        }

        InsertPRTGJobs::dispatchSync($uniq_id, $latency);

        return response()->json([
            'status' => 'success',
            'message' => 'Record Stored',
            'data' => [
                'uniq_id' => $uniq_id,
                'latency' => $latency,
            ],
        ]);
    }
}
