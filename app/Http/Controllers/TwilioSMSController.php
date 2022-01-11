<?php

namespace App\Http\Controllers;
use Twilio\Rest\Client;
use PragmaRX\Google2FAQRCode\Google2FA;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;






class TwilioSMSController extends Controller
{
    public function checkCode($code){
        //https://github.com/sonata-project/GoogleAuthenticator/blob/3.x/sample/example.php
        $g = new GoogleAuthenticator();
        $user = \App\Models\User::first();
        if($user->google2fa_secret){
            $secret =    $user->google2fa_secret;
        } else {
            $secret = $g->generateSecret();
            $user->google2fa_secret = $secret;
            $user->save();
        }
        if ($g->checkCode($secret, $code, 0)) {
            echo $code." : YES";
        } else {
            echo $code." : NO";
        }
    }

    public function my() {
        $g = new GoogleAuthenticator();
        $user = \App\Models\User::first();
        if($user->google2fa_secret){
            $secret =    $user->google2fa_secret;
        } else {
            $secret = $g->generateSecret();
            $user->google2fa_secret = $secret;
            $user->save();
        }
        $data['code'] =  $g->getCode($secret);
        $data['url'] =  GoogleQrUrl::generate('chregu', $secret, 'GoogleAuthenticatorExample');
        return view("my")->with($data);
    }

    public function index()
    {
        $receiverNumber = "+923353134197";
        $messageText = "This is the testing from Altaf Korejo";
        try {

            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_TOKEN");
            $twilio_number = getenv("TWILIO_FROM");

            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'body' => $messageText
            ]);

            dd('SMS Sent Successfully.');
        } catch (\Exception $e) {
            dd("Error: " . $e->getMessage());
        }
    }
    
    public function twoFa(){
        $user = \App\Models\User::first();
        $google2fa = new Google2FA();

        if($user->google2fa_secret){
            $secKey =    $user->google2fa_secret;
        } else {
            $secKey = $google2fa->generateSecretKey();
            $user->google2fa_secret = $secKey;
            $user->save();
        }
        
        $data['google2fa_url'] = $google2fa->getQRCodeUrl(
            $user->name,
            $user->email,
            $secKey
        );
        var_dump($data);

       //return view('test')->with($data);
    }
    public function verify(){
        $google2fa = new Google2FA();
        $user = \App\Models\User::first();
        $secret = "458905";
        $valid = $google2fa->verifyKey($user->google2fa_secret, $secret,0);
        var_dump($valid);
    }

    // public function twoFa()
    // {
    //     // $google2fa = app('pragmarx.google2fa');

    //     $google2fa = new Google2FA();

    //     $user = User::first();
    //     if(!$user->google2fa_secret){
    //         $user->google2fa_secret = $google2fa->generateSecretKey();
    //         $user->save();
    //     }
    //     $secKey = $user->google2fa_secret;

    //         $data['qrCodeUrl'] = $google2fa->getQRCodeInline(
    //             'pragmarx',
    //             'google2fa@pragmarx.com',
    //             $secKey
    //             );
    //         return view("test")->with($data);
    // }

    // public function verify(){
    //     $google2fa = new Google2FA();
    //     $user = User::first();
    //     if(!$user->google2fa_secret){
    //         $user->google2fa_secret = $google2fa->generateSecretKey();
    //         $user->save();
    //     }
    //     $valid = $google2fa->verifyKey($user->google2fa_secret, "817302");
    //     var_dump($valid);
    // }
}
