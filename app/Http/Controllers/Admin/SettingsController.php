<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Tour\Tour;
use App\Models\Hotel\Hotel;
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
            $settings->data = json_encode($request->tours);
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
            $settings->data = json_encode($request->hotels);
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

    public function setHomeAd(Request $request) {
        $validator = Validator::make($request->all(), [
            'ad' => 'required',
            'thumbnail' => 'required'
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Set Ad failed', [$validator->errors()->first()], []);
        }


        $data = $request->ad;
        $image = $this->saveImg($request->thumbnail, 'images/uploads/Ad');

        $data["thumbnail_path"] = '/images/uploads/Ad/' . $image;

        $settings = Setting::where("key", "ad") ->first();

        if ($settings) {
            $settings->data = json_encode($data);
            $settings->save();
        } else {
            $settings = Setting::create([
                "key" => "ad",
                "data" => json_encode($data)
            ]);
        }

        if ($settings) {
            return $this->jsondata(true, null, 'Home Ad setted successfuly', [], []);
        }
    }

    public function getHomeAd() {
        $ad = Setting::where("key", "ad") ->first();

        if ($ad)
            return json_decode($ad->data);
    }
    public function setHomeAd2(Request $request) {
        $validator = Validator::make($request->all(), [
            'ad2' => 'required',
            'thumbnail' => 'required'
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Set Ad2 failed', [$validator->errors()->first()], []);
        }


        $data = $request->ad2;
        $image = $this->saveImg($request->thumbnail, 'images/uploads/Ad2');

        $data["thumbnail_path"] = '/images/uploads/Ad2/' . $image;

        $settings = Setting::where("key", "ad2") ->first();

        if ($settings) {
            $settings->data = json_encode($data);
            $settings->save();
        } else {
            $settings = Setting::create([
                "key" => "ad2",
                "data" => json_encode($data)
            ]);
        }

        if ($settings) {
            return $this->jsondata(true, null, 'Home Ad2 setted successfuly', [], []);
        }
    }

    public function getHomeAd2() {
        $Ad2 = Setting::where("key", "ad2") ->first();

        if ($Ad2)
            return json_decode($Ad2->data);
    }
}
