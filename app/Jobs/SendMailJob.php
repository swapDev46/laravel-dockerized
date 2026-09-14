<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Sending mail...");
        sleep(5);

        Mail::raw('Hello from Laravel + Redis!', function ($message) {
            $message
                ->to('demo@chinchu.com')
                ->subject('Redis Queue Test');
        });
    }
}
