<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function getHomeAd() {
        $ad = Setting::where("key", "ad")->first();
        $ad2 = Setting::where("key", "ad_2")->first();

        return response()->json([

            "ad" => $ad ? json_encode($ad->data) : null,
            "ad2" => $ad2 ? json_encode($ad2->data) : null,
        ], 200);
    }
}
