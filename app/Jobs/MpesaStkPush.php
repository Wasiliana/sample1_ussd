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

use App\Models\ApiKey;
use App\Models\GroupContacts;

class MpesaStkPush implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $amount;
    protected $phoneNumber;

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

        try {

            $client = new Client();
            $url = env("STK_LINK") . "WASILIANA-9008";
            // $params = array('phone_number' => $this->phoneNumber, 'amount' => $this->amount, 'type' => 'app');
            $postArr = [];
            $headers = [];

            $request = new Request('POST', $url, $headers, json_encode($postArr));
            $response = $client->sendAsync($request)->wait();

        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
