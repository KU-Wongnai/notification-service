<?php
use App\Models\User;
use App\Mail\ResetPasswordEmail;
use App\Mail\WelcomeRestaurantEmail;
use App\Mail\WelcomeRiderEmail;
use App\Mail\WelcomeUserEmail;
use App\Notifications\WelcomeNewRider;
use App\Notifications\WelcomeNewUser;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification as FacadesNotification;

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

// ----------------------------------------------------------------------------------------------------------------------------

// Email Notification part

Route::get('/welcome/user/email/{toEmail}', function ($toEmail) {   // ยินดีต้อนรับหลังสมัครสมาชิก

    Mail::to($toEmail)->send(new WelcomeUserEmail());

    return response()->json(['message' => 'Welcome user email notification sent.']);
});

Route::get('/welcome/rider/email/{toEmail}', function ($toEmail) {  // ยินดีต้อนรับหลังสมัครเป็น Rider

    Mail::to($toEmail)->send(new WelcomeRiderEmail());

    return response()->json(['message' => 'Welcome rider email notification sent.']);
});

Route::get('/welcome/restaurant/email/{toEmail}', function ($toEmail) {  // ยินดีต้อนรับหลังสมัครเป็น Restaurant

    Mail::to($toEmail)->send(new WelcomeRestaurantEmail());

    return response()->json(['message' => 'Welcome restaurant email notification sent.']);
});

Route::get('/reset-password/email/{toEmail}', function ($toEmail) {  // ส่งอีเมลล์เพื่อรีเซ็ตรหัสผ่าน

    Mail::to($toEmail)->send(new ResetPasswordEmail());

    return response()->json(['message' => 'Reset password email notification sent.']);
});

// ----------------------------------------------------------------------------------------------------------------------------

