<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;

class SettingsController extends Controller
{
    public function getHomeAd() {
        $ad = Settings::where("key", "ad")->first();

        if ($ad)
            return json_encode($ad->data);
    }
}
