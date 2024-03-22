<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking\Request as BookingRequest;
class BookingController extends Controller
{
    public function get() {
        // Fetch the requests with eager loading of the "user" relationship
        $requests = BookingRequest::with("user")->latest()->take(200)->get();
      
        // Create a copy of the requests to avoid modifying the original collection
        $requestsToUpdate = clone $requests;
      
        // Update the "seen" flag for each request in the copy
        foreach ($requestsToUpdate as $req) {
          $req->seen = 1;
          $req->save();
        }
      
        // Return the original requests (without modifications)
        return $requests;
      }
    public function getNew() {
        $requests = BookingRequest::with("user")->where("seen", 0)->latest()->take(200)->get();
        return $requests;
    }
}
