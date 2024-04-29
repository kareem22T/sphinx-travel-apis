<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\Language;

class DestinationController extends Controller
{
    public function getDestinations(Request $request) {
        $sortKey =($request->sort && $request->sort == "HP") || ( $request->sort && $request->sort == "LP") ? "lowest_room_price" :"avg_rating";
        $sortWay = $request->sort && $request->sort == "HP" ? "desc" : ( $request->sort && $request->sort  == "LP" ? "asc" : "desc");
        // $currency_id = 2;
        $lang = Language::where("key", $request->lang ? $request->lang : "EN")->first();

        $destinations = Destination::with(
            [
                'hotels' => function ($q) use ($lang, $sortKey, $sortWay) {
                    $q->with([
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
                    ])->orderBy($sortKey, $sortWay);
                }
                , 'tours'
            ]
        )->get();
        return $destinations;
    }
}
