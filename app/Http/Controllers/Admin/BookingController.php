<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking\Request as BookingRequest;
use Illuminate\Support\Facades\Validator;
use App\Traits\DataFormController;
use App\Traits\PushNotificationTrait;

class BookingController extends Controller
{
    use DataFormController, PushNotificationTrait;

    public function get() {
        $requests = BookingRequest::with("user")->latest()->take(200)->get();
        return $requests;
    }
    public function seen() {
        $requests = BookingRequest::with("user")->where("seen", 0)->latest()->take(200)->get();
        $requestsToUpdate = clone $requests;

        // Update the "seen" flag for each request in the copy
        foreach ($requestsToUpdate as $req) {
          $req->seen = 1;
          $req->save();
        }
        return $requests;
    }
    public function getNew() {
        $requests = BookingRequest::with("user")->where("seen", 0)->latest()->take(200)->get();
        return $requests;
    }

    public function approve(Request $request) {
        $validator = Validator::make($request->all(), [
            'req_id' => 'required',
        ], );

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Approve failed', [$validator->errors()->first()], []);
        }

        $booking = BookingRequest::with("user")->find($request->req_id);

        if ($booking) :
            if ($booking->status === 1) {
                $booking->status = 2;
                $this->pushNotification("Booking Confirmation", "Your Booking have been confirmed successfuly", $booking->user->id);
            } else if ($booking->status === 2) {
                $booking->status = 3;
                $this->pushNotification("Booking Completed", "Your Booking have been completed successfuly", $booking->user->id);

                $this->pushNotification("Rate your experience now", "Your Booking have been completed would you want to rate your experince?", $booking->user->id);

                $message = Message::create(
                    [
                        "msg" => $booking->booking_details,
                        "user_id" => $booking->user->id,
                        "is_user_sender" => false,
                        "type" => 2,
                    ]
                );
            }
            $booking->save();
        endif;

        return $this->jsondata(true, null, 'Successfuly operation', [], []);
    }
    public function cancel(Request $request) {
        $validator = Validator::make($request->all(), [
            'req_id' => 'required',
        ], );

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Approve failed', [$validator->errors()->first()], []);
        }

        $booking = BookingRequest::with("user")->find($request->req_id);

        if ($booking) :
            $booking->status = 4;
            $this->pushNotification("Booking Canceled", "Your Booking have not completed unfortunately", $booking->user->id);
            $booking->save();
        endif;

        return $this->jsondata(true, null, 'Successfuly operation', [], []);
    }
}
