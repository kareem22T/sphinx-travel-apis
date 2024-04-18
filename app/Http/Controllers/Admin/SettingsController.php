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
            $tours = Tour::whereIn('id', json_decode($settings->data))->with(["names", "gallery"])->get();
            if ($tours)
                return $tours;
        }
    }
}
