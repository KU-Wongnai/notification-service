<?php

use App\Mail\ResetPasswordEmail;
use App\Mail\WelcomeRiderEmail;
use App\Mail\WelcomeUserEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/welcome/user/{toEmail}', function ($toEmail) {

    Mail::to($toEmail)->send(new WelcomeUserEmail());

    return response()->json(['message' => 'Welcome user email notification sent.']);
});

Route::get('/welcome/rider/{toEmail}', function ($toEmail) {

    Mail::to($toEmail)->send(new WelcomeRiderEmail());

    return response()->json(['message' => 'Welcome rider email notification sent.']);
});

Route::get('/reset-password/{toEmail}', function ($toEmail) {

    Mail::to($toEmail)->send(new ResetPasswordEmail());

    return response()->json(['message' => 'Reset password email notification sent.']);
});
