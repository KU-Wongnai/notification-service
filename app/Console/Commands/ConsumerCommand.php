<?php

namespace App\Console\Commands;

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
use App\Http\Controllers\UserCreateController;
use App\Mail\ResetPasswordEmail;
use App\Mail\WelcomeRestaurantEmail;
use App\Mail\WelcomeRiderEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\RabbitMQReceiver;
use Illuminate\Support\Facades\Mail;
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

class ConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info("[ProcessRabbitMQMessage] Handling job");

        $receiver = new RabbitMQReceiver();

        echo "Connected to RabbitMQ";

        $receiver->declareExchange('events.notification', 'topic');
        $receiver->bindQueueToExchange('to', 'events.notification', 'email.*');

        $receiver->consume('to', function ($message) {
            
            Log::info("[ProcessRabbitMQMessage] Received a message");
            
            $data = json_decode($message, true);

            // Type of email to send to users
            if ($data['type'] === 'email.welcome.user') {
                Log::info('Received email.welcome.user event', $data);
                echo "Received email.welcome.user event" . json_encode($data);
                Mail::to($data['to'])->send(new WelcomeUserEmail());
            }

            if ($data['type'] === 'email.welcome.restaurant') {
                Log::info('Received email.welcome.restaurant event', $data);
                echo "Received email.welcome.restaurant event" . json_encode($data);
                Mail::to($data['to'])->send(new WelcomeRestaurantEmail());
            }

            if ($data['type'] === 'email.welcome.rider') {
                Log::info('Received email.welcome.rider event', $data);
                echo "Received email.welcome.rider event" . json_encode($data);
                Mail::to($data['to'])->send(new WelcomeRiderEmail());
            }

            if ($data['type'] === 'email.reset.password') {
                Log::info('Received email.reset.password event', $data);
                echo "Received email.reset.password event" . json_encode($data);
                Mail::to($data['to'])->send(new ResetPasswordEmail());
            }

            // In-app notification to users
                // user part
            if ($data['type'] === 'noti.WelcomeNewUser') {
                Log::info('Received noti.WelcomeNewUser event', $data);
                echo "Received noti.WelcomeNewUser event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new WelcomeNewUser());
                broadcast(new EventsWelcomeNewUser($user));
            }

            if ($data['type'] === 'noti.UserReviewHasReplied') {
                Log::info('Received noti.UserReviewHasReplied event', $data);
                echo "Received noti.UserReviewHasReplied event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new UserReviewHasReplied());
                broadcast(new EventsUserReviewHasReplied($user));
            }

            if ($data['type'] === 'noti.UserReviewNewFollower') {
                Log::info('Received noti.UserReviewNewFollower event', $data);
                echo "Received noti.UserReviewNewFollower event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new UserReviewNewFollower());
                broadcast(new EventsUserReviewNewFollower($user));
            }

            if ($data['type'] === 'noti.UserReviewWeekly') {
                Log::info('Received noti.UserReviewWeekly event', $data);
                echo "Received noti.UserReviewWeekly event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new UserReviewWeeklyChallenge());
                broadcast(new EventsUserReviewWeeklyChallenge($user));
            }

            if ($data['type'] === 'noti.ThankUser') {
                Log::info('Received noti.ThankUser event', $data);
                echo "Received noti.ThankUser event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new ThankUser());
                broadcast(new EventsThankUser($user));
            }

            if ($data['type'] === 'noti.UserDeliveryOrder') {
                Log::info('Received noti.UserDeliveryOrder event', $data);
                echo "Received noti.UserDeliveryOrder event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new UserDeliveryOrder());
                broadcast(new EventsUserDeliveryOrder($user));
            }

            if ($data['type'] === 'noti.UserDeliveryWait') {
                Log::info('Received noti.UserDeliveryWait event', $data);
                echo "Received noti.UserDeliveryWait event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new UserDeliveryWait());
                broadcast(new EventsUserDeliveryWait($user));
            }

            if ($data['type'] === 'noti.UserDeliveryFinished') {
                Log::info('Received noti.UserDeliveryFinished event', $data);
                echo "Received noti.UserDeliveryFinished event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new UserDeliveryFinished());
                broadcast(new EventsUserDeliveryFinished($user));
            }

            if ($data['type'] === 'noti.UserDeliveryRemindReview') {
                Log::info('Received noti.UserDeliveryRemindReview event', $data);
                echo "Received noti.UserDeliveryRemindReview event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new UserDeliveryRemindReview());
                broadcast(new EventsUserDeliveryRemindReview($user));
            }

            if ($data['type'] === 'noti.UserDeliveryReorder') {
                Log::info('Received noti.UserDeliveryReorder event', $data);
                echo "Received noti.UserDeliveryReorder event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new UserDeliveryReorder());
                broadcast(new EventsUserDeliveryReorder($user));
            }

            if ($data['type'] === 'noti.UserSuccessPayment') {
                Log::info('Received noti.UserSuccessPayment event', $data);
                echo "Received noti.UserSuccessPayment event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new UserSuccessPayment());
                broadcast(new EventsUserSuccessPayment($user));
            }

            if ($data['type'] === 'noti.UserOrderCanceled') {
                Log::info('Received noti.UserOrderCanceled event', $data);
                echo "Received noti.UserOrderCanceled event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new UserOrderCanceled());
                broadcast(new EventsUserOrderCanceled($user));
            }
            
            if ($data['type'] === 'noti.UserReviewHasComment') {
                Log::info('Received noti.UserReviewHasComment event', $data);
                echo "Received noti.UserReviewHasComment event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new UserReviewHasComment());
                broadcast(new EventsUserReviewHasComment($user));
            }

                // rider part
            if ($data['type'] === 'noti.WelcomeNewRider') {
                Log::info('Received noti.WelcomeNewRider event', $data);
                echo "Received noti.WelcomeNewRider event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new WelcomeNewRider());
                broadcast(new EventsWelcomeNewRider($user));
            }

            if ($data['type'] === 'noti.RiderNewOrder') {
                Log::info('Received noti.RiderNewOrder event', $data);
                echo "Received noti.RiderNewOrder event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new RiderNewOrder());
                broadcast(new EventsRiderNewOrder($user));
            }

            if ($data['type'] === 'noti.RiderOrderOnWay') {
                Log::info('Received noti.RiderOrderOnWay event', $data);
                echo "Received noti.RiderOrderOnWay event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new RiderOrderOnWay());
                broadcast(new EventsRiderOrderOnWay($user));
            }

            if ($data['type'] === 'noti.RiderOrderFinished') {
                Log::info('Received noti.RiderOrderFinished event', $data);
                echo "Received noti.RiderOrderFinished event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new RiderOrderFinished());
                broadcast(new EventsRiderOrderFinished($user));
            }

            if ($data['type'] === 'noti.RiderSafetyReminder') {
                Log::info('Received noti.RiderSafetyReminder event', $data);
                echo "Received noti.RiderSafetyReminder event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new RiderSafetyReminder());
                broadcast(new EventsRiderSafetyReminder($user));
            }

            if ($data['type'] === 'noti.ThankRider') {
                Log::info('Received noti.ThankRider event', $data);
                echo "Received noti.ThankRider event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new ThankRider());
                broadcast(new EventsThankRider($user));
                
            }

            if ($data['type'] === 'noti.RiderOrderCanceled') {
                Log::info('Received noti.RiderOrderCanceled event', $data);
                echo "Received noti.RiderOrderCanceled event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new RiderOrderCanceled());
                broadcast(new EventsRiderOrderCanceled($user));
            }


            if ($data['type'] === 'noti.RiderCreateAccount') {
                Log::info('Received noti.RiderCreateAccount event', $data);
                echo "Received noti.RiderCreateAccount event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new RiderCreateAccount());
                broadcast(new EventsRiderCreateAccount($user));
            }

            if ($data['type'] === 'noti.RiderRejectedAccount') {
                Log::info('Received noti.RiderRejectedAccount event', $data);
                echo "Received noti.RiderRejectedAccount event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new RiderRejectedAccount());
                broadcast(new EventsRiderRejectedAccount($user));
            }

                // restaurant part
            if ($data['type'] === 'noti.WelcomeNewRestaurant') {
                Log::info('Received noti.WelcomeNewRestaurant event', $data);
                echo "Received noti.WelcomeNewRestaurant event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new WelcomeNewRestaurant());
                broadcast(new EventsWelcomeNewRestaurant($user));
            }

            if ($data['type'] === 'noti.RestaurantHasReview') {
                Log::info('Received noti.RestaurantHasReview event', $data);
                echo "Received noti.RestaurantHasReview event" . json_encode($data);
                $user = new UserCreateController();
                $user = $user->create($data['to']);
                $user->notify(new RestaurantHasReview());
                broadcast(new EventsRestaurantHasReview($user));
            }

        });
    }
}