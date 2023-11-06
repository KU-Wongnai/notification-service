<?php

use App\Events\WelcomeNewRider as EventsWelcomeNewRider;
use App\Events\WelcomeNewUser as EventsWelcomeNewUser;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserCreateController;
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
use Illuminate\Support\Facades\Hash;
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
Route::get('/user/noti/welcome/{id}', [NotificationController::class, 'sendWelcomeNewUser']);// welcome new user

Route::get('/user/noti/review-has-replied/{id}', [NotificationController::class, 'sendUserReviewHasReplied']);

Route::get('/user/noti/review-new-follower/{id}', [NotificationController::class, 'sendUserReviewNewFollower']);

Route::get('/user/noti/review-weekly-challenge/{id}', [NotificationController::class, 'sendUserReviewWeekly']);

Route::get('/user/noti/thank/{id}', [NotificationController::class, 'sendThankUser']);


    // User delivery part
Route::get('/user/noti/delivery-Order/{id}', [NotificationController::class, 'sendUserDeliveryOrder']);

Route::get('/user/noti/delivery-wait/{id}', [NotificationController::class, 'sendUserDeliveryWait']);

Route::get('/user/noti/delivery-finished/{id}', [NotificationController::class, 'sendUserDeliveryFinished']);

Route::get('/user/noti/delivery-remind-review/{id}', [NotificationController::class, 'sendUserDeliveryRemindReview']);

Route::get('/user/noti/delivery-reorder/{id}', [NotificationController::class, 'sendUserDeliveryReorder']);

Route::get('/user/noti/success-payment/{id}', [NotificationController::class, 'sendUserSuccessPayment']);

Route::get('/user/noti/order-canceled/{id}', [NotificationController::class, 'sendUserOrderCanceled']);

Route::get('/user/noti/review-has-comment/{id}', [NotificationController::class, 'sendUserReviewHasComment']);


    // Rider part

Route::get('/rider/noti/welcome/{id}', [NotificationController::class, 'sendWelcomeNewRider']);

Route::get('/rider/noti/new-order/{id}', [NotificationController::class, 'sendRiderNewOrder']);

Route::get('/rider/noti/order-on-way/{id}', [NotificationController::class, 'sendRiderOrderOnWay']);

Route::get('/rider/noti/order-finished/{id}', [NotificationController::class, 'sendRiderOrderFinished']);

Route::get('/rider/noti/safety-reminder/{id}', [NotificationController::class, 'sendRiderSafetyReminder']);

Route::get('/rider/noti/thank/{id}', [NotificationController::class, 'sendThankRider']);

Route::get('/rider/noti/order-canceled/{id}', [NotificationController::class, 'sendRiderOrderCanceled']);

Route::get('/rider/noti/create-account/{id}', [NotificationController::class, 'sendRiderCreateAccount']);

Route::get('/rider/noti/rejected-account/{id}', [NotificationController::class, 'sendRiderRejectedAccount']);

    // Restaurant part
Route::get('/restaurant/noti/welcome/{id}', [NotificationController::class, 'sendWelcomeNewRestaurant']);

Route::get('/restaurant/noti/has-review/{id}', [NotificationController::class, 'sendRestaurantHasReview']);

Route::get('/restaurant/noti/create-account/{id}', [NotificationController::class, 'sendRestaurantCreateAccount']);

Route::get('/restaurant/noti/rejected-account/{id}', [NotificationController::class, 'sendRestaurantRejectedAccount']);



// ----------------------------------------------------------------------------------------------------------------------------

Route::get('/notifications/{id}', [NotificationController::class, 'getAllNotification']);

Route::post('/notifications/{userId}/mark-as-read/{notiId}', [NotificationController::class, 'markAsRead']);


// Route::get('/noti/trytry/{id}',[NotificationController::class, 'sendTry']); 


// Route::get('/test/{id}', function ($id) {   // this user has new reply on review

//     $user = new UserCreateController();
//     $user = $user->create($id);
//     $user->notify(new WelcomeNewUser());

//     return response()->json($user->notifications->first());
// });
