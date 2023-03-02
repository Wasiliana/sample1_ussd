<?php

namespace App\Http\Controllers;

use App\Jobs\MpesaStkPush;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;


class UssdController extends Controller
{

    public function index(Request $request)
    {
        // Get the request parameters
        $postData =  $request->all();
        $sessionId =  $request->sessionId;
        $phoneNumber =  $request->phoneNumber;
        $serviceCode =  $request->serviceCode;
        $text =  $request->text;

        // Check if the session exists
        if (file_exists(storage_path('app/' . $sessionId . '.json')) && $text != null) {

            // load existing session data
            $ussdSes = json_decode(file_get_contents(storage_path('app/' . $sessionId . '.json')), true);

            // Use the session data to determine the current step 
            switch ($ussdSes['step']) {
                case '1':
                    // Prompt the user to enter a valid amount
                    if (!is_numeric($text)) {
                        $message = "CON please enter a valid amount.\n";

                        // Update the session data
                        $sessionData['phoneNumber'] = $phoneNumber;
                        $sessionData['serviceCode'] = $serviceCode;
                        $sessionData['text'] = $text == null ? '' : $text;
                        $sessionData['step'] = 1;
                        file_put_contents(storage_path('app/' . $sessionId . '.json'), json_encode($sessionData));

                        return $message;
                    }

                    // Proceed with the payment
                    $message = "CON your about to pay wasiliana Ksh" . $text . ".\n";
                    $message .= "1. Proceed \n";

                    // Update the session data
                    $sessionData['phoneNumber'] = $phoneNumber;
                    $sessionData['serviceCode'] = $serviceCode;
                    $sessionData['text'] = $text;
                    $sessionData['step'] = 2;
                    file_put_contents(storage_path('app/' . $sessionId . '.json'), json_encode($sessionData));

                    return $message;
                    break;
                case '2':
                    // Prompt the user to enter a valid option
                    $userOption = explode('*', $text);
                    if (!is_numeric($userOption[1]) || $userOption[1] != 1) {
                        $message = "CON please enter a valid option. \n";
                        $message .= "1. Proceed \n";

                        // Update the session data
                        $sessionData['phoneNumber'] = $phoneNumber;
                        $sessionData['serviceCode'] = $serviceCode;
                        $sessionData['text'] = $ussdSes['text'];
                        $sessionData['step'] = 2;
                        file_put_contents(storage_path('app/' . $sessionId . '.json'), json_encode($sessionData));

                        return $message;
                    }

                    // End the session and trigger the payment
                    $message = "END thank you for using Wasiliana you will relieve an Mpesa prompt to complete payment.\n";

                    // Update the session data
                    $sessionData['phoneNumber'] = $phoneNumber;
                    $sessionData['serviceCode'] = $serviceCode;
                    $sessionData['text'] = $ussdSes['text'];
                    $sessionData['step'] = 1;
                    file_put_contents(storage_path('app/' . $sessionId . '.json'), json_encode($sessionData));

                    // Write your stk or payment code here 
                    // $job = (new MpesaStkPush($ussdSes['text'], $phoneNumber))->delay(Carbon::now()->addSeconds(5));
                    // dispatch(new MpesaStkPush(5, $phoneNumber));
                    MpesaStkPush::dispatch($ussdSes['text'], $phoneNumber);
                    // $tip_request_data = array(
                    //     'accessType' => 'express',
                    //     'accountNumber' => '0' . '-' . '0' . '-' . '95209', //account number of person receiving tip
                    //     'phoneNumber' => $phoneNumber, //person sending money
                    //     'billAmount' => $ussdSes['text']
                    // );

                    // Http::withHeaders([
                    //     'accept' => 'application/json',
                    //     'Content-Type' => 'application/json'
                    // ])->post('https://m-tip.app/payments/saf/auth.php', $tip_request_data);

                    return $message;
                    break;

                default:

                    break;
            }
        } else {

            // if the session is empty

            $message = "CON Welcome to wasiliana enter amount to proceed. \n";

            // Update the session data
            $sessionData['phoneNumber'] = $phoneNumber;
            $sessionData['serviceCode'] = $serviceCode;
            $sessionData['text'] = $text == null ? '' : $text;
            $sessionData['step'] = 1;
            file_put_contents(storage_path('app/' . $sessionId . '.json'), json_encode($sessionData));

            return $message;
        }
    }
}
