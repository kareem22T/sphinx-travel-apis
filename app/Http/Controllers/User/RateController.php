<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\DataFormController;
use App\Traits\PushNotificationTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\Hotel_rating;
use App\Models\Tour_rating;
use App\Models\Hotel\Hotel;
use App\Models\Message;
use Illuminate\Support\Facades\Http;


class RateController extends Controller
{
    use PushNotificationTrait, DataFormController;
    public function rateHotel(Request $request) {
        $user = $request->user();
        if ($user) {
            $validator = Validator::make($request->all(), [
                'hotel_id' => 'required',
                'msg_id' => 'required',
                'staff' => 'required',
                'facilities' => 'required',
                'cleanliness' => 'required',
                'comfort' => 'required',
                'money' => 'required',
                'location' => 'required',
            ], );

            if ($validator->fails()) {
                return $this->jsondata(false, null, 'Rate failed', [$validator->errors()->first()], []);
            }


            $hotel = Hotel::find($request->hotel_id);

            if ($hotel) {
                $hotel->avg_rating = (((int) $hotel->avg_rating * (int) $hotel->num_of_ratings) + (((int) $request->staff + (int) $request->facilities + (int) $request->cleanliness + (int) $request->comfort + (int) $request->location) / 6)) / ((int) $hotel->num_of_ratings + 1) ;
                $hotel->avg_staff_rating = (((int) $hotel->staff * (int) $hotel->num_of_ratings) + (int) $request->staff) / ((int) $hotel->num_of_ratings + 1);
                $hotel->avg_facilities_rating = (((int) $hotel->facilities * (int) $hotel->num_of_ratings) + (int) $request->facilities) / ((int) $hotel->num_of_ratings + 1);
                $hotel->avg_cleanliness_rating = (((int) $hotel->cleanliness * (int) $hotel->num_of_ratings) + (int) $request->cleanliness) / ((int) $hotel->num_of_ratings + 1);
                $hotel->avg_comfort_rating = (((int) $hotel->comfort * (int) $hotel->comfort) + (int) $request->comfort) / ((int) $hotel->num_of_ratings + 1);
                $hotel->avg_money_rating = (((int) $hotel->money * (int) $hotel->money) + (int) $request->money) / ((int) $hotel->money + 1);
                $hotel->avg_location_rating = (((int) $hotel->location * (int) $hotel->location) + (int) $request->location) / ((int) $hotel->location + 1);
                $hotel->num_of_ratings = (int) $hotel->num_of_ratings + 1 ;
                $hotel->save();
            }

            $rate = Hotel_rating::create([
                "hotel_id" => $request->hotel_id,
                "staff" => (int) $request->staff,
                "facilities" => (int) $request->facilities,
                "cleanliness" => (int) $request->cleanliness,
                "comfort" => (int) $request->comfort,
                "money" => (int) $request->money,
                "location" => (int) $request->location,
            ]);

            if ($rate) {
                $msg = Message::find($request->msg_id);
                if ($msg)
                    $msg->delete();

                $this->pushNotification("Rating Completed", "Your Rating have submited successfully, thanks for rating", $user->id);

                $message = Message::create(
                    [
                        "msg" => "Thanks for rating!",
                        "user_id" => $user->id,
                        "is_user_sender" => false,
                        "type" => 1,
                    ]
                );

                $serverKey = 'AAAA-0IfxKc:APA91bEose-nnQ_9aWfGbJkJCx8c-w66gahaB5BgS3TXVKWDph-Wd41myHvV9ME-yjwUAARdH9_xC9b8nLUn6MCaKto3kKyn40cL3jnO1kGrqo3lDrW4uPY7cNSRLCTcNaNOdyQG8mT8';
                $deviceToken = "/topics/MsgUser_" . $user->id;

                $response = Http::withHeaders([
                    'Authorization' => 'key=' . $serverKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $deviceToken,
                    'notification' => [
                        'title' => "Rate your experience now",
                        'body' => "Your Booking have been completed would you want to rate your experince",
                        'icon' => "https://sphinx-travel.ykdev.online/11Sphinx.png"
                    ],
                ]);

                return $this->jsondata(true, null, 'Rating successfuly', [$validator->errors()->first()], []);

            }
        }
    }
    public function rateTour(Request $request) {
        $user = $request->user();
        if ($user) {
            $validator = Validator::make($request->all(), [
                'msg_id' => 'required',
                'tour_id' => 'required',
                'rate' => 'required',
                'describe' => 'required',
            ], );

            if ($validator->fails()) {
                return $this->jsondata(false, null, 'Rate failed', [$validator->errors()->first()], []);
            }


            $rate = Tour_rating::create([
                "tour_id" => $request->tour_id,
                "user_id" => $user->id,
                "describe" => $request->describe,
                "approved" => $request->describe ? false : true,
                "rate" => (int) $request->rate,
            ]);

            if ($rate) {
                $msg = Message::find($request->msg_id);
                if ($msg)
                    $msg->delete();

                $this->pushNotification("Rating Completed", "Your Rating have submited successfully, thanks for rating", $user->id);

                $message = Message::create(
                    [
                        "msg" => "Thanks for rating!",
                        "user_id" => $user->id,
                        "is_user_sender" => false,
                        "type" => 1,
                    ]
                );

                $serverKey = 'AAAA-0IfxKc:APA91bEose-nnQ_9aWfGbJkJCx8c-w66gahaB5BgS3TXVKWDph-Wd41myHvV9ME-yjwUAARdH9_xC9b8nLUn6MCaKto3kKyn40cL3jnO1kGrqo3lDrW4uPY7cNSRLCTcNaNOdyQG8mT8';
                $deviceToken = "/topics/MsgUser_" . $user->id;

                $response = Http::withHeaders([
                    'Authorization' => 'key=' . $serverKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $deviceToken,
                    'notification' => [
                        'title' => "Rate your experience now",
                        'body' => "Your Booking have been completed would you want to rate your experince",
                        'icon' => "https://sphinx-travel.ykdev.online/11Sphinx.png"
                    ],
                ]);

                return $this->jsondata(true, null, 'Rating successfuly', [$validator->errors()->first()], []);

            }
        }
    }
}
