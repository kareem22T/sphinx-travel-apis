<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking\Request as BookingRequest;
class BookingController extends Controller
{
    public function get() {
        $requests = BookingRequest::latest()->take(200)->get();
        return $requests;
    }
}
