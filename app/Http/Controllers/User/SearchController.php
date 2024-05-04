<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel\Hotel;
use App\Models\Hotel\Rooms\Room;
use App\Models\Language;
use App\Models\Currency;
use App\Models\Setting;
use App\Models\Tour\Tour;

class SearchController extends Controller
{
    public function getHotels(Request $request) {
        $sortKey =($request->sort && $request->sort == "HP") || ( $request->sort && $request->sort == "LP") ? "lowest_room_price" :"avg_rating";
        $sortWay = $request->sort && $request->sort == "HP" ? "desc" : ( $request->sort && $request->sort  == "LP" ? "asc" : "desc");
        // $currency_id = 2;
        $lang = Language::where("key", $request->lang ? $request->lang : "EN")->first() ? Language::where("key", $request->lang ? $request->lang : "EN")->first() : Language::where("key", "EN")->first();
        $currency_id = Currency::find($request->currency_id) ? Currency::find($request->currency_id)->id : Currency::first()->id;

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
            "rooms" => function ($q) use ($lang, $currency_id) {
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
                }, "prices" => function ($q) use ($lang, $currency_id) {
                    $q->with(['currency' => function ($Q) use ($lang) {
                        $Q->with(["names" => function ($q) use ($lang) {
                            if ($lang)
                            $q->where("language_id", $lang->id);
                        }]);
                    }])->where("currency_id", $currency_id);
                }]);
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
        ])->when($request->search_words, function ($query) use ($request) {
            return $query->whereHas('names', function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search_words}%");
            })->orWhereHas('descriptions', function ($q) use ($request) {
                $q->where('description', 'LIKE', "%{$request->search_words}%");
            });
            // Add other whereHas clauses for additional related fields
        })->orderBy($sortKey, $sortWay)->get();

        return response()->json(
            $hotels
        , 200);
    }
    public function getTours(Request $request) {
        $lang = Language::where("key", $request->lang ? $request->lang : "EN")->first() ? Language::where("key", $request->lang ? $request->lang : "EN")->first() : Language::where("key", "EN")->first();
        $currency_id = Currency::find($request->currency_id) ? Currency::find($request->currency_id)->id : Currency::first()->id;

        $sortKey =($request->sort && $request->sort == "HP") || ( $request->sort && $request->sort == "LP") ? "lowest_package_price" :"avg_rating";
        $sortWay = $request->sort && $request->sort == "HP" ? "desc" : ( $request->sort && $request->sort  == "LP" ? "asc" : "desc");

        $tours = Tour::with([
            "ratings" => function($q) {
                $q->with("user")->where("approved", true);
            },
            "titles" => function ($q) use ($lang) {
            if ($lang)
            $q->where("language_id", $lang->id);
        }, "intros" => function ($q) use ($lang) {
            if ($lang)
            $q->where("language_id", $lang->id);
        }, "gallery", "days" => function ($q) use ($lang) {$q->with(["titles" => function ($q) use ($lang) {
            if ($lang)
            $q->where("language_id", $lang->id);
        }, "descriptions" => function ($q) use ($lang) {
        $q->where("language_id", $lang->id);
        }]);}, "locations" => function ($q) use ($lang) {
            if ($lang)
            $q->where("language_id", $lang->id);
        }, "transportations" => function ($q) use ($lang) {
            if ($lang)
            $q->where("language_id", $lang->id);
        }, "includes" => function ($q) use ($lang) {
            if ($lang)
            $q->where("language_id", $lang->id);
        }, "excludes" => function ($q) use ($lang) {
            if ($lang)
            $q->where("language_id", $lang->id);
        },
        "packages" => function ($q) use ($lang, $currency_id) {
            $q->with(["titles" => function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            }, "descriptions" => function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            }, "prices" => function ($q) use ($lang, $currency_id) {
                $q->with(['currency' => function ($Q) use ($lang) {
                    $Q->with(["names" => function ($q) use ($lang) {
                        if ($lang)
                        $q->where("language_id", $lang->id);
                    }]);
                }])->where("currency_id", $currency_id);
            }, "points" => function ($q) use ($lang) {
                $q->with(["titles" => function ($q) use ($lang) {
                    if ($lang)
                    $q->where("language_id", $lang->id);
                }, "descriptions" => function ($q) use ($lang) {
                    if ($lang)
                    $q->where("language_id", $lang->id);
                }
            ]);
            }
            ]);
        },])->when($request->search_words, function ($query) use ($request) {
            return $query->whereHas('names', function ($q) use ($request) {
                $q->where('title', 'LIKE', "%{$request->search_words}%");
            })->orWhereHas('descriptions', function ($q) use ($request) {
                $q->where('content', 'LIKE', "%{$request->search_words}%");
            });
            // Add other whereHas clauses for additional related fields
        })->orderBy($sortKey, $sortWay)->get();
        return $tours;
    }
}
