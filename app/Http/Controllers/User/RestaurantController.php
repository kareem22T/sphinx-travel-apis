<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resturant\Resturant;
use App\Models\Language;

class RestaurantController extends Controller
{
    public function nearestRestaurants(Request $request)
    {
        $lang = Language::where("key", $request->lang ? $request->lang : "EN")->first();

        if ($request->lat && $request->lng) :
        $limit = 25;
        $maxDistance = 10;
        $haversine = "(6371 * acos(cos(radians($request->lat)) * cos(radians(lat)) * cos(radians(lng) - radians($request->lng)) + sin(radians($request->lat)) * sin(radians(lat))))";

        return Resturant::select('*')
            ->with(["titles" => function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            }, "descriptions"  => function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            }])
            ->selectRaw("{$haversine} AS distance")
            ->whereRaw("{$haversine} <= ?", [$maxDistance])
            ->orderBy('distance', 'asc')
            ->take($limit)
            ->get();
        else: 
            return Resturant::select('*')
            ->take($limit)
            ->get();
        endif;
    }
}
