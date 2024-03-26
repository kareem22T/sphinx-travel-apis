<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking\Request as BookingRequest;
use Illuminate\Support\Facades\Validator;
use App\Traits\DataFormController;

class BookingController extends Controller
{
    use DataFormController;

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

        $booking = BookingRequest::find($request->req_id);

        if ($booking) :
            if ($booking->status === 1) {
                $booking->status = 2;
            } else if ($booking->status === 2) {
                $booking->status = 3;
            }
            $booking->save();
        endif;

        return $this->jsondata(true, null, 'Successfuly operation', [], []);
    }
}
