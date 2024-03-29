<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car\Car;
use App\Models\Language;

class CarController extends Controller
{
    public function getCars(Request $request) {
        // $currency_id = 2;
        $lang = Language::where("key", $request->lang)->first();
        $cars = Car::latest()->with([
            "titles" => function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            },
            "descriptions"=> function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            },
            "types" => function ($q) use ($lang) {
                if ($lang)
                $q->where("language_id", $lang->id);
            },
            "prices",
            "gallery",
            "features" => function ($q) use ($lang) {
                $q->with(["names" => function ($qe) use ($lang) {
                    if ($lang)
                    $qe->where("language_id", $lang->id);
                }]);
            },
        ])->get();

        return response()->json(
            $cars
        , 200);
    }

}
