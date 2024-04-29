<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function getDestinations() {
        $destinations = Destination::all();
        return $destinations;
    }
}
