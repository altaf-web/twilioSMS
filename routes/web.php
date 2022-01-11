<?php

use App\Http\Controllers\TwilioSMSController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get("sendSMS",[TwilioSMSController::class,'index']);
Route::get("/twoFa",[TwilioSMSController::class,'twoFa']);
Route::get("/test",[TwilioSMSController::class,'verify']);

Route::get("my",[TwilioSMSController::class,'my']);
Route::get("check/{code}",[TwilioSMSController::class,'checkCode']);
