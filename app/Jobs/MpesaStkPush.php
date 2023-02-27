<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;

class MpesaStkPush implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $amount;
    public $phoneNumber;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($amount, $phoneNumber)
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
        $client = new Client();
        $url = env("STK_LINK") . "WASILIANA-9008";
        $params = array('phone_number' => $this->phoneNumber, 'amount' => $this->amount, 'type' => 'app');
        $promise = $client->request(
            'POST',
            $url,
            [
                'form_params' => $params
            ]
        );
    }
}