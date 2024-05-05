<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Activity;
use App\Traits\DataFormController;
use Illuminate\Support\Facades\Validator;
use App\Traits\SavePhotoTrait;
use Illuminate\Support\Facades\File;

class ActivitiesController extends Controller
{
    use DataFormController;
    use SavePhotoTrait;

    public function get() {
        return $Activities = Activity::latest()->get();
    }

    public function add(Request $request) {

        $validator = Validator::make($request->all(), [
            'name_en' => 'required',
            'name_ar' => 'required',
            'desc_en' => 'required',
            'desc_ar' => 'required',
            'thumbnail_path' => 'required',
            // 'name' => 'required',
        ], [
            'name_en.required' => 'please enter Activity name in English',
            'name_ar.required' => 'please enter Activity name in Arabic',
            'desc_ar.required' => 'please enter Activity description in English',
            'desc_en.required' => 'please enter Activity description in Arabic',
            'thumbnail_path.required' => 'please Upload Activity thumbnail',
            // 'name.required' => 'please enter Activity name',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'add failed', [$validator->errors()->first()], []);
        }

        $image = $this->saveImg($request->thumbnail_path, 'images/uploads/Activity');

        $crete_Activity = Activity::create([
            "name_en" => $request->name_en,
            "name_ar" => $request->name_ar,
            "desc_ar" => $request->desc_ar,
            "desc_en" => $request->desc_en,
            "thumbnail_path" => '/images/uploads/Activity/' . $image,
            // "name" => $request->name
        ]);


        if ($crete_Activity)
            return  $this->jsondata(true, null, 'Activity has added successfuly', [], []);
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'name_en' => 'required',
            'name_ar' => 'required',
            'desc_en' => 'required',
            'desc_ar' => 'required',
            // 'name' => 'required',
        ], [
            'name_en.required' => 'please enter Activity name in English',
            'name_ar.required' => 'please enter Activity name in Arabic',
            'desc_ar.required' => 'please enter Activity description in English',
            'desc_en.required' => 'please enter Activity description in Arabic',
            'thumbnail_path.required' => 'please Upload Activity thumbnail',
            // 'name.required' => 'please enter Activity name',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'add failed', [$validator->errors()->first()], []);
        }


        $Activity = Activity::find($request->id);
        if ($request->thumbnail_path) {
            File::delete($Activity->thumbnail_path);
            $image = $this->saveImg($request->thumbnail_path, 'images/uploads/Activity');
            $Activity->thumbnail_path = '/images/uploads/Activity/' . $image;
        }

        $Activity->name_en = $request->name_en;
        $Activity->name_ar = $request->name_ar;
        $Activity->desc_ar = $request->desc_ar;
        $Activity->desc_en = $request->desc_en;

        $Activity->save();

        if ($Activity)
            return  $this->jsondata(true, null, 'Activity has updated successfuly', [], []);
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'delete failed', [$validator->errors()->first()], []);
        }

        $currnecy = Activity::find($request->id);
        $currnecy->delete();

        if ($currnecy)
            return  $this->jsondata(true, null, 'Activity has deleted successfuly', [], []);
    }
    public function activity(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Show failed', [$validator->errors()->first()], []);
        }

        $activity = Activity::find($request->id);

        return $activity;
    }


}

