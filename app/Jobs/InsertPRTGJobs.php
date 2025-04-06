<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class InsertPRTGJobs implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public $uniq_id, public $latency
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $prtg_url = env('PRTG_URL');
        $uniq_id = $this->uniq_id;
        $latency = $this->latency;

        if ($latency != -1) {
            $req = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post("$prtg_url/$uniq_id", [
                'prtg' => [
                    'result' => [
                        [
                            'channel' => 'latency',
                            'value' => $latency,
                            'unit' => 'TimeResponse',
                            'float' => 1
                        ]
                    ]
                ]
            ]);
        } else {
            $req = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post("$prtg_url/$uniq_id", [
                'prtg' => [
                    'error' => -1,
                    'text' => 'Tidak ada record latency yang diterima'
                ]
            ]);
        }


    }
}
