<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel\Hotel;
use App\Models\Hotel\Rooms\Room;
use App\Models\Language;
use App\Models\Setting;

class HotelController extends Controller
{
    public function getHotels(Request $request) {
        // $currency_id = 2;
        $lang = Language::where("key", $request->lang)->first();
        $hotels = Hotel::latest()->with([
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

    public function getRomms(Request $request) {
        // $currency_id = 2;
        $lang = Language::where("key", $request->lang)->first();
        $hotels = Room::latest()->with(["features" => function ($q) use ($lang) {
            $q->with(["names" => function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            }]);
        }, "gallery", "names" => function ($q) use ($lang) {
            if ($lang)
            $q->where("language_id", $lang->id);
        },"descriptions" => function ($q) use ($lang) {
            if ($lang)
            $q->where("language_id", $lang->id);
        }, "prices", "hotel" => function ($q) use ($lang) {
            $q->with([
                "ratings",
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
            ]);
        }])->take(15)->get();

        return response()->json(
            $hotels
        , 200);
    }

    public function getCottages(Request $request) {
        // $currency_id = 2;
        $lang = Language::where("key", $request->lang)->first();
        $hotels = Hotel::latest()->where("type", "Cottage")->with([
            "ratings",
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
                }, "prices", "gallery"]);
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

    public function getHomeHotels(Request $request) {
        // $currency_id = 2;
        $lang = Language::where("key", $request->lang ? $request->lang : "EN")->first();
        $settings = Setting::where("key", "hotels") ->first();
        $hotels = [];


        if ($settings)
            $hotels = Hotel::whereIn('id', json_decode($settings->data))->with([
                "ratings",
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
                    }, "prices", "gallery"]);
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
