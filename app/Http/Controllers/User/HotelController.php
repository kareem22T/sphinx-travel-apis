<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel\Hotel;
use App\Models\Language;

class HotelController extends Controller
{
    public function getHotels(Request $request) {
        // $currency_id = 2;
        $lang = Language::where("key", $request->lang)->first();
        $hotels = Hotel::with([
            "names" => function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            },
            "descriptions"=> function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            },
            "addresses" => function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            },
            "rooms" => function ($q) use ($lang) {
                $q->with(["gallery", "names" => function ($q) use ($lang) {
                    if ($lang)
                    $q->where("language_id", $lang->id);
                }, "prices"]);
            },
            "slogans" => function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            },
            "gallery",
            "features" => function ($q) use ($lang) {
                $q->with(["names" => function ($qe) use ($lang) {
                    if ($lang)
                    $qe->where("language_id", $lang->id);
                }]);
            },
            "reasons" => function ($q) use ($lang) {
                $q->with(["names" => function ($q) use ($lang) {
                    if ($lang)
                    $q->where("language_id", $lang->id);
                }, "descriptions" => function ($q) use ($lang) {
                    if ($lang)
                    $q->where("language_id", $lang->id);
                }]);
            },
        ])->get();

        return response()->json(
            $hotels
        , 200);
    }

}
