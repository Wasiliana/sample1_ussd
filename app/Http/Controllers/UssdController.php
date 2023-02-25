<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;


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

                    $this->trigger_stk($ussdSes['text'], $phoneNumber, $message);

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

    // Write your stk or payment code here 
    public function trigger_stk($amount, $phone_number, $message)
    {

        $client = new Client();
        $url = env("STK_LINK") . "WASILIANA-9008";
        $params = array('phone_number' => $phone_number, 'amount' => $amount, 'type' => 'app');
        $promise = $client->requestAsync(
            'POST',
            $url,
            [
                'form_params' => $params
            ]
        );

        try {
            $promise->wait();
        } catch (\Exception $ex) {
            ## Handle                       
        }

        // file_put_contents(storage_path('app/stk-log.json'), json_encode($response->getBody()->getContents()));


        // Http::post($url, [
        //     'form_params' => $params
        // ]);


        // return $message;
    }
}
