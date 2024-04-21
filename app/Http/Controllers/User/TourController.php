<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour\Tour;
use App\Models\Language;
use App\Models\Setting;

class TourController extends Controller
{
    public function getTours(Request $request) {
        $lang = Language::where("key", $request->lang ? $request->lang : "EN")->first();

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
        "packages" => function ($q) use ($lang) {
            $q->with(["titles" => function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            }, "descriptions" => function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            }, "prices", "points" => function ($q) use ($lang) {
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
        },])->orderBy($sortKey, $sortWay)->get();
        return $tours;
    }
    public function getTour(Request $request) {
        $lang = Language::where("key", $request->lang ? $request->lang : "EN")->first();

        $tour = Tour::with([
        "ratings",
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
        "packages" => function ($q) use ($lang) {
            $q->with(["titles" => function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            }, "descriptions" => function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            }, "prices", "points" => function ($q) use ($lang) {
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
        },])->find($request->id);
        return $tour;
    }
    public function getHomeTours(Request $request) {
        $lang = Language::where("key", $request->lang ? $request->lang : "EN")->first();
        $settings = Setting::where("key", "tours") ->first();
        $tour = [];

        if ($settings) {
            $tour = Tour::latest()->whereIn('id', json_decode($settings->data))->with([
            "ratings",
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
            "packages" => function ($q) use ($lang) {
                $q->with(["titles" => function ($q) use ($lang) {
                    if ($lang)
                    $q->where("language_id", $lang->id);
                }, "descriptions" => function ($q) use ($lang) {
                    if ($lang)
                    $q->where("language_id", $lang->id);
                }, "prices", "points" => function ($q) use ($lang) {
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
            },])->get();

            return $tour;
        }
    }
}
