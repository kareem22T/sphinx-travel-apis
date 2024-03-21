<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\DataFormController;

use App\Models\Booking\Request as BookingRequest;

class BookingController extends Controller
{
    use DataFormController;
    
    public function get(Request $request) {
        $user = $request->user();
        $history = $user->bookings()->get(); // Fetch bookings as an array
        return response()->json($history); // Convert to JSON and return response
    }

    public function create(Request $request) {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'booking_details' => 'required',
        ], [
            'booking_details.required' => 'please enter booking details',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Booking failed', [$validator->errors()->first()], []);
        }

        $booking = BookingRequest::create([
            "booking_details" => $request->booking_details,
            "status" => 1,
            "user_id" => $user->id
        ]);

        // notify admin at app

        // send email to addmins

        if ($booking)
            return $this->jsondata(true, null, 'You request has been received successfully, we will contact you soon!', [], []);
    }
}
