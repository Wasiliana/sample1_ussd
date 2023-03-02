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



    protected $dataArray;
    protected $amount;
    protected $phoneNumber;

    /**
     * Create a new job instance.
     *
     * @return void
     */


    public function __construct($dataArray)
    {
        $this->dataArray = $dataArray;
        // $this->amount = $amount;
        // $this->phoneNumber = $phoneNumber;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        try {

            $amount = $this->dataArray['amount'];
            $phoneNumber = $this->dataArray['phoneNumber'];

            $client = new Client();
            $url = env("STK_LINK") . "WASILIANA-9008";
            $params = array('phone_number' => $phoneNumber, 'amount' => $amount, 'type' => 'app');
            $headers = [
                'Content-Type' => 'application/json'
            ];

            $request = new Request('POST', $url, $headers, json_encode($params));
            $response = $client->sendAsync($request)->wait();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
