<?php

namespace App\Http\Controllers;

use App\Events\RestaurantHasReview as EventsRestaurantHasReview;
use App\Events\RiderCreateAccount as EventsRiderCreateAccount;
use App\Events\RiderNewOrder as EventsRiderNewOrder;
use App\Events\RiderOrderCanceled as EventsRiderOrderCanceled;
use App\Events\RiderOrderFinished as EventsRiderOrderFinished;
use App\Events\RiderOrderOnWay as EventsRiderOrderOnWay;
use App\Events\RiderRejectedAccount as EventsRiderRejectedAccount;
use App\Events\RiderSafetyReminder as EventsRiderSafetyReminder;
use App\Events\ThankRider as EventsThankRider;
use App\Events\ThankUser as EventsThankUser;
use App\Events\UserDeliveryFinished as EventsUserDeliveryFinished;
use App\Events\UserDeliveryOrder as EventsUserDeliveryOrder;
use App\Events\UserDeliveryRemindReview as EventsUserDeliveryRemindReview;
use App\Events\UserDeliveryReorder as EventsUserDeliveryReorder;
use App\Events\UserDeliveryWait as EventsUserDeliveryWait;
use App\Events\UserOrderCanceled as EventsUserOrderCanceled;
use App\Events\UserReviewHasComment as EventsUserReviewHasComment;
use App\Events\UserReviewHasReplied as EventsUserReviewHasReplied;
use App\Events\UserReviewNewFollower as EventsUserReviewNewFollower;
use App\Events\UserReviewWeeklyChallenge as EventsUserReviewWeeklyChallenge;
use App\Events\UserSuccessPayment as EventsUserSuccessPayment;
use App\Events\WelcomeNewRestaurant as EventsWelcomeNewRestaurant;
use App\Events\WelcomeNewRider as EventsWelcomeNewRider;
use App\Events\WelcomeNewUser as EventsWelcomeNewUser;
use App\Models\User;
use App\Mail\ResetPasswordEmail;
use App\Mail\WelcomeRestaurantEmail;
use App\Mail\WelcomeRiderEmail;
use App\Mail\WelcomeUserEmail;
use App\Notifications\RestaurantHasReview;
use App\Notifications\RiderCreateAccount;
use App\Notifications\RiderNewOrder;
use App\Notifications\RiderOrderCanceled;
use App\Notifications\RiderOrderFinished;
use App\Notifications\RiderOrderOnWay;
use App\Notifications\RiderRejectedAccount;
use App\Notifications\RiderSafetyReminder;
use App\Notifications\ThankRider;
use App\Notifications\ThankUser;
use App\Notifications\UserDeliveryFinished;
use App\Notifications\UserDeliveryOrder;
use App\Notifications\UserDeliveryRemindReview;
use App\Notifications\UserDeliveryReorder;
use App\Notifications\UserDeliveryWait;
use App\Notifications\UserOrderCanceled;
use App\Notifications\UserReviewHasComment;
use App\Notifications\UserReviewHasReplied;
use App\Notifications\UserReviewNewFollower;
use App\Notifications\UserReviewWeeklyChallenge;
use App\Notifications\UserSuccessPayment;
use App\Notifications\WelcomeNewRestaurant;
use App\Notifications\WelcomeNewRider;
use App\Notifications\WelcomeNewUser;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification as FacadesNotification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function sendTry(string $id)
    {
        $user = User::find($id);

        $user->notify(new \App\Notifications\RiderNewOrder());

        // Notify frontend
        return response()->json($user->notifications->first());
    }

// In app part
    // User part
    public function sendWelcomeNewUser(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new WelcomeNewUser());
        broadcast(new EventsWelcomeNewUser($user));
        return response()->json($user->notifications->first());
    }


    public function sendUserReviewHasReplied(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new UserReviewHasReplied());
        broadcast(new EventsUserReviewHasReplied($user));
        return response()->json($user->notifications->first());
    }


    public function sendUserReviewNewFollower(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new UserReviewNewFollower());
        broadcast(new EventsUserReviewNewFollower($user));
        return response()->json($user->notifications->first());
    }


    public function sendUserReviewWeekly(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new UserReviewWeeklyChallenge());
        broadcast(new EventsUserReviewWeeklyChallenge($user));
        return response()->json($user->notifications->first());
    }


    public function sendThankUser(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new ThankUser());
        broadcast(new EventsThankUser($user));
        return response()->json($user->notifications->first());
    }

    // User delivery part
    public function sendUserDeliveryOrder(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new UserDeliveryOrder());
        broadcast(new EventsUserDeliveryOrder($user));
        return response()->json($user->notifications->first());
    }


    public function sendUserDeliveryWait(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new UserDeliveryWait());
        broadcast(new EventsUserDeliveryWait($user));
        return response()->json($user->notifications->first());
    }


    public function sendUserDeliveryFinished(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new UserDeliveryFinished());
        broadcast(new EventsUserDeliveryFinished($user));
        return response()->json($user->notifications->first());
    }


    public function sendUserDeliveryRemindReview(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new UserDeliveryRemindReview());
        broadcast(new EventsUserDeliveryRemindReview($user));
        return response()->json($user->notifications->first());
    }


    public function sendUserDeliveryReorder(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new UserDeliveryReorder());
        broadcast(new EventsUserDeliveryReorder($user));
        return response()->json($user->notifications->first());
    }

    public function sendUserSuccessPayment(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new UserSuccessPayment());
        broadcast(new EventsUserSuccessPayment($user));
        return response()->json($user->notifications->first());
    }

    public function sendUserOrderCanceled(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new UserOrderCanceled());
        broadcast(new EventsUserOrderCanceled($user));
        return response()->json($user->notifications->first());
    }
    
    public function sendUserReviewHasComment(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new UserReviewHasComment());
        broadcast(new EventsUserReviewHasComment($user));
        return response()->json($user->notifications->first());
    }

    public function sendRiderCreateAccount(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new RiderCreateAccount());
        broadcast(new EventsRiderCreateAccount($user));
        return response()->json($user->notifications->first());
    }

    public function sendRiderRejectedAccount(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new RiderRejectedAccount());
        broadcast(new EventsRiderRejectedAccount($user));
        return response()->json($user->notifications->first());
    }

    // Rider part
    public function sendWelcomeNewRider(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new WelcomeNewRider());
        broadcast(new EventsWelcomeNewRider($user));
        return response()->json($user->notifications->first());
    }


    public function sendRiderNewOrder(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new RiderNewOrder());
        broadcast(new EventsRiderNewOrder($user));
        return response()->json($user->notifications->first());
    }


    public function sendRiderOrderOnWay(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new RiderOrderOnWay());
        broadcast(new EventsRiderOrderOnWay($user));
        return response()->json($user->notifications->first());
    }


    public function sendRiderOrderFinished(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new RiderOrderFinished());
        broadcast(new EventsRiderOrderFinished($user));
        return response()->json($user->notifications->first());
    }


    public function sendRiderSafetyReminder(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new RiderSafetyReminder());
        broadcast(new EventsRiderSafetyReminder($user));
        return response()->json($user->notifications->first());
    }


    public function sendThankRider(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new ThankRider());
        broadcast(new EventsThankRider($user));
        return response()->json($user->notifications->first());
    }

    
    public function sendRiderOrderCanceled(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new RiderOrderCanceled());
        broadcast(new EventsRiderOrderCanceled($user));
        return response()->json($user->notifications->first());
    }

    // Restaurant part
    public function sendWelcomeNewRestaurant(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new WelcomeNewRestaurant());
        broadcast(new EventsWelcomeNewRestaurant($user));
        return response()->json($user->notifications->first());
    }



    public function sendRestaurantHasReview(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        $user->notify(new RestaurantHasReview());
        broadcast(new EventsRestaurantHasReview($user));
        return response()->json($user->notifications->first());
    }


// ----------------------------------------------------------------------------------------------------------------------------

    public function getAllNotification(string $id)
    {
        $user = new UserCreateController();
        $user = $user->create($id);

        return response()->json($user->notifications);
    }

    public function markAsRead(string $userId, string $notiId)
    {
        $user = new UserCreateController();
        $user = $user->create($userId);
        $noti = $user->notifications->where('id', $notiId)->first();
        $noti->markAsRead();
        return response()->json($noti);
    }

    
}
