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

        $postArr['phoneNumber'] = $phoneNumber;
        $postArr['amount'] = $amount;

        dispatch(new StkPush($phoneNumber, $amount));
        // sleep(2);
        
    }
}
