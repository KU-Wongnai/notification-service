<?php

use App\Http\Controllers\NotificationController;
use App\Models\User;
use App\Mail\ResetPasswordEmail;
use App\Mail\WelcomeRestaurantEmail;
use App\Mail\WelcomeRiderEmail;
use App\Mail\WelcomeUserEmail;
use App\Notifications\RestaurantHasReview;
use App\Notifications\RiderNewOrder;
use App\Notifications\RiderOrderFinished;
use App\Notifications\RiderOrderOnWay;
use App\Notifications\RiderSafetyReminder;
use App\Notifications\ThankRider;
use App\Notifications\ThankUser;
use App\Notifications\UserDeliveryFinished;
use App\Notifications\UserDeliveryOrder;
use App\Notifications\UserDeliveryRemindReview;
use App\Notifications\UserDeliveryReorder;
use App\Notifications\UserDeliveryWait;
use App\Notifications\UserReviewHasReplied;
use App\Notifications\UserReviewNewFollower;
use App\Notifications\UserReviewWeeklyChallenge;
use App\Notifications\WelcomeNewRestaurant;
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

Route::get('/welcome/user/email/{toEmail}', function ($toEmail) {   // welcome email after user register

    Mail::to($toEmail)->send(new WelcomeUserEmail());

    return response()->json(['message' => 'Welcome user email notification sent.']);
});

Route::get('/welcome/rider/email/{toEmail}', function ($toEmail) {  // welcome email after rider register

    Mail::to($toEmail)->send(new WelcomeRiderEmail());

    return response()->json(['message' => 'Welcome rider email notification sent.']);
});

Route::get('/welcome/restaurant/email/{toEmail}', function ($toEmail) {  // welcome email after restaurant register

    Mail::to($toEmail)->send(new WelcomeRestaurantEmail());

    return response()->json(['message' => 'Welcome restaurant email notification sent.']);
});

Route::get('/reset-password/email/{toEmail}', function ($toEmail) {  // send reset password email

    Mail::to($toEmail)->send(new ResetPasswordEmail());

    return response()->json(['message' => 'Reset password email notification sent.']);
});

// ----------------------------------------------------------------------------------------------------------------------------


// Notification in App part

    // User review part
Route::get('/user/noti/welcome/{id}', function ($id) {  // welcome new user

    $user = User::find($id);
    // Notification::send($user, new WelcomeNewUser());
    $user->notify(new WelcomeNewUser());

    return response()->json($user->notifications->first());
});

Route::get('/user/noti/review-has-replied/{id}', function ($id) {   // this user has new reply on review

    $user = User::find($id);
    $user->notify(new UserReviewHasReplied());

    return response()->json($user->notifications->first());
});

Route::get('/user/noti/review-new-follower/{id}', function ($id) {  // this user has new follower

    $user = User::find($id);
    $user->notify(new UserReviewNewFollower());

    return response()->json($user->notifications->first());
});

Route::get('/user/noti/review-weekly-challenge/{id}', function ($id) {  // this user receive noti weekly challenge for review

    $user = User::find($id);
    $user->notify(new UserReviewWeeklyChallenge());

    return response()->json($user->notifications->first());
});

Route::get('/user/noti/thank/{id}', function ($id) {    // thank this user for use app

    $user = User::find($id);
    $user->notify(new ThankUser());

    return response()->json($user->notifications->first());
});


    // User delivery part
Route::get('/user/noti/delivery-Order/{id}', function ($id) {   // this user order food

    $user = User::find($id);
    $user->notify(new UserDeliveryOrder());

    return response()->json($user->notifications->first());
});

Route::get('/user/noti/delivery-wait/{id}', function ($id) { // rider accept order and this user wait

    $user = User::find($id);
    $user->notify(new UserDeliveryWait());

    return response()->json($user->notifications->first());
});

Route::get('/user/noti/delivery-finished/{id}', function ($id) { // this user receive order

    $user = User::find($id);
    $user->notify(new UserDeliveryFinished());

    return response()->json($user->notifications->first());
});

Route::get('/user/noti/delivery-remind-review/{id}', function ($id) { // reminder this user to review after delivery finished

    $user = User::find($id);
    $user->notify(new UserDeliveryRemindReview());

    return response()->json($user->notifications->first());
});

Route::get('/user/noti/delivery-reorder/{id}', function ($id) { // this user can reorder

    $user = User::find($id);
    $user->notify(new UserDeliveryReorder());

    return response()->json($user->notifications->first());
});



    // Rider part

Route::get('/rider/noti/welcome/{id}', function ($id) {  // welcome new rider

    $user = User::find($id);
    $user->notify(new WelcomeNewRider());

    return response()->json($user->notifications->first());
});

Route::get('/rider/noti/new-order/{id}', function ($id) {  // this Rider receive new order

    $user = User::find($id);
    $user->notify(new RiderNewOrder());

    return response()->json($user->notifications->first());
});

Route::get('/rider/noti/order-on-way/{id}', function ($id) { // this Rider on the way to deliver order

    $user = User::find($id);
    $user->notify(new RiderOrderOnWay());

    return response()->json($user->notifications->first());
});

Route::get('/rider/noti/order-finished/{id}', function ($id) { // this Rider finish deliver order

    $user = User::find($id);
    $user->notify(new RiderOrderFinished());

    return response()->json($user->notifications->first());
});

Route::get('/rider/noti/safety-reminder/{id}', function ($id) { // Rider safety reminder

    $user = User::find($id);
    $user->notify(new RiderSafetyReminder());

    return response()->json($user->notifications->first());
});

Route::get('/rider/noti/thank/{id}', function ($id) {   // thank this rider

    $user = User::find($id);
    $user->notify(new ThankRider());

    return response()->json($user->notifications->first());
});



    // Restaurant part
Route::get('/restaurant/noti/welcome/{id}', function ($id) {    // welcome new restaurant

    $user = User::find($id);
    $user->notify(new WelcomeNewRestaurant());

    return response()->json($user->notifications->first());
});

Route::get('/restaurant/noti/has-review/{id}', function ($id) { // noti when restaurant has review

    $user = User::find($id);
    $user->notify(new RestaurantHasReview());

    return response()->json($user->notifications->first());
});

// ----------------------------------------------------------------------------------------------------------------------------

Route::get('/notifications/{id}', function ($id) {     // all notification of this user

    $user = User::find($id);
    return response()->json($user->notifications);
});


// Route::get('/noti/trytry/{id}',[NotificationController::class, 'sendTry']); 
