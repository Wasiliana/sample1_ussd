<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;

class StkPush implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $amount;
    protected $phoneNumber;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phoneNumber,$amount)
    {
        $this->amount = $amount;
        $this->phoneNumber = $phoneNumber;
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
            'phoneNumber' => $this->phoneNumber, //person sending money
            'billAmount' => $this->amount
        );

        Http::withHeaders([
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post('https://m-tip.app/payments/saf/auth.php', $tip_request_data);
    }
}
