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
        $hotels = Hotel::latest()->with([
            "ratings" => function ($q) {
                $q->select(\DB::raw('AVG(staff + facilities + cleanliness + comfort + money + location) AS average_rating'));
            },
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
                $q->with(["features" => function ($q) use ($lang) {
                    $q->with(["names" => function ($qe) use ($lang) {
                        if ($lang)
                        $qe->where("language_id", $lang->id);
                    }]);
                }, "gallery", "names" => function ($q) use ($lang) {
                    if ($lang)
                    $q->where("language_id", $lang->id);
                },"descriptions" => function ($q) use ($lang) {
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
            "tours" => function($q) use ($lang) {
                $q->with(["titles" => function ($q) use ($lang) {
                    if ($lang)
                    $q->where("language_id", $lang->id);
                }, "intros" => function ($q) use ($lang) {
                    if ($lang)
                    $q->where("language_id", $lang->id);
                }, "gallery"]);
            }
        ])->get();

        return response()->json(
            $hotels
        , 200);
    }

    public function getCottages(Request $request) {
        // $currency_id = 2;
        $lang = Language::where("key", $request->lang)->first();
        $hotels = Hotel::latest()->where("type", "Cottage")->with([
            "averageRating",
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
                $q->with(["features" => function ($q) use ($lang) {
                    $q->with(["names" => function ($qe) use ($lang) {
                        if ($lang)
                        $qe->where("language_id", $lang->id);
                    }]);
                }, "names" => function ($q) use ($lang) {
                    if ($lang)
                    $q->where("language_id", $lang->id);
                }, "descriptions" => function ($q) use ($lang) {
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
            "tours" => function($q) use ($lang) {
                $q->with(["titles" => function ($q) use ($lang) {
                    if ($lang)
                    $q->where("language_id", $lang->id);
                }, "intros" => function ($q) use ($lang) {
                    if ($lang)
                    $q->where("language_id", $lang->id);
                }, "gallery"]);
            }
        ])->get();

        return response()->json(
            $hotels
        , 200);
    }

    public function getHotelNearstRestaurante(Request $request) {
        $lang = Language::where("key", $request->lang ? $request->lang : "EN")->first();

        $hotel= Hotel::find($request->id);

        return $hotel->nearestRestaurants(10, 10, $lang);
    }
}
