<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Tour\Tour;
use App\Traits\DataFormController;
use App\Traits\SavePhotoTrait;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    use DataFormController;
    use SavePhotoTrait;

    public function setHomeTours(Request $request) {
        $validator = Validator::make($request->all(), [
            'tours' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Set Tours failed', [$validator->errors()->first()], []);
        }


        $settings = Setting::where("key", "tours") ->first();

        if ($settings) {
            $settings->data = json_decode($request->tours);
            $settings->save();
        } else {
            $settings = Setting::create([
                "key" => "tours",
                "data" => json_encode($request->tours)
            ]);
        }

        if ($settings) {
            return $this->jsondata(true, null, 'Home Tours setted successfuly', [], []);
        }
    }

    public function getHomeTours() {
        $settings = Setting::where("key", "tours") ->first();

        if ($settings) {
            $tours = Tour::whereIn('id', json_decode($settings->data))->with(["titles", "intros", "gallery"])->get();
            if ($tours)
                return $tours;
        }
    }

    public function setHomeHotels(Request $request) {
        $validator = Validator::make($request->all(), [
            'hotels' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Set Hotels failed', [$validator->errors()->first()], []);
        }


        $settings = Setting::where("key", "hotels") ->first();

        if ($settings) {
            $settings->data = json_decode($request->hotels);
            $settings->save();
        } else {
            $settings = Setting::create([
                "key" => "hotels",
                "data" => json_encode($request->hotels)
            ]);
        }

        if ($settings) {
            return $this->jsondata(true, null, 'Home Hotel setted successfuly', [], []);
        }
    }

    public function getHomeHotels() {
        $settings = Setting::where("key", "hotels") ->first();

        if ($settings) {
            $hotels = Hotel::whereIn('id', json_decode($settings->data))->with(["names", "slogans", "gallery"])->get();
            if ($hotels)
                return $hotels;
        }
    }
}
