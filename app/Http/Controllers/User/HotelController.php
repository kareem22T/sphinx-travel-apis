<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel\Hotel;

class HotelController extends Controller
{
    public function getHotels() {
        // $currency_id = 2;
        
        $hotels = Hotel::with([
            "names",
            "descriptions",
            "addresses",
            "rooms" => function ($q) {
                $q->with(["gallery", "names", "prices"]);
            },
            "slogans",
            "gallery",
            "features" => function ($q) {
                $q->with("names");
            },
            "reasons",
        ])->get();

        return response()->json(
            $hotels
        , 200);
    }

}
