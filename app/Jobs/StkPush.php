<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StkPush implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tip_request_data = array(
            'accessType' => 'express',
            'accountNumber' => '0' . '-' . '0' . '-' . '95209', //account number of person receiving tip
            'phoneNumber' => $phoneNumber, //person sending money
            'billAmount' => $amount
        );

        Http::withHeaders([
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post('https://m-tip.app/payments/saf/auth.php', $tip_request_data);
    }
}
