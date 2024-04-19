<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function getHomeAd() {
        $ad = Setting::where("key", "ad")->first();

        if ($ad)
            return json_decode($ad->data);
    }
}
