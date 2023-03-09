<?php

namespace App\Traits;

use App\Jobs\StkPush;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

trait Common
{

    public function stkFunction($phoneNumber, $amount)
    {

        // dispatch(new App\Jobs\StkPush($postData));
        // sleep(2);
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
