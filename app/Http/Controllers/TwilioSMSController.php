<?php

namespace App\Http\Controllers;
use Twilio\Rest\Client;

class TwilioSMSController extends Controller
{
    public function index() {
       $receiverNumber = "+923353134197";
        $messageText = "This is the testing from Altaf Korejo";
        try {
  
            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_TOKEN");
            $twilio_number = getenv("TWILIO_FROM");

            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number, 
                'body' => $messageText]);
  
            dd('SMS Sent Successfully.');
  
        } catch (\Exception $e) {
            dd("Error: ". $e->getMessage());
        }
    }
}
