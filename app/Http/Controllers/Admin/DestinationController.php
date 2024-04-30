<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Destination;
use App\Traits\DataFormController;
use Illuminate\Support\Facades\Validator;
use App\Traits\SavePhotoTrait;
use Illuminate\Support\Facades\Storage;
class DestinationController extends Controller
{
    use DataFormController;
    use SavePhotoTrait;

    public function get() {
        return $Destinations = Destination::latest()->get();
    }

    public function add(Request $request) {

        $validator = Validator::make($request->all(), [
            'name_en' => 'required',
            'name_ar' => 'required',
            'thumbnail_path' => 'required',
            // 'name' => 'required',
        ], [
            'name_en.required' => 'please enter destination name in English',
            'name_ar.required' => 'please enter destination name in Arabic',
            'thumbnail_path.required' => 'please Upload destination thumbnail',
            // 'name.required' => 'please enter destination name',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'add failed', [$validator->errors()->first()], []);
        }

        $image = $this->saveImg($request->thumbnail_path, 'images/uploads/Destination');

        $crete_destination = Destination::create([
            "name_en" => $request->name_en,
            "name_ar" => $request->name_ar,
            "thumbnail_path" => '/images/uploads/Destination/' . $image,
            // "name" => $request->name
        ]);


        if ($crete_destination)
            return  $this->jsondata(true, null, 'Destination has added successfuly', [], []);
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'name_en' => 'required',
            'name_ar' => 'required',
            // 'name' => 'required',
        ], [
            'name_en.required' => 'please enter destination name in English',
            'name_ar.required' => 'please enter destination name in Arabic',
            // 'name.required' => 'please enter destination name',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'add failed', [$validator->errors()->first()], []);
        }


        $destination = Destination::find($request->id);
        if ($request->thumbnail_path) {
            Storage::delete($destination->thumbnail_path);
            $image = $this->saveImg($request->thumbnail_path, 'images/uploads/Destination');
            $destination->thumbnail_path = '/images/uploads/Destination/' . $image;
        }

        $destination->name_en = $request->name_en;
        $destination->name_ar = $request->name_ar;

        $destination->save();

        if ($destination)
            return  $this->jsondata(true, null, 'Destination has updated successfuly', [], []);
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'delete failed', [$validator->errors()->first()], []);
        }

        $currnecy = Destination::find($request->id);
        $currnecy->delete();

        if ($currnecy)
            return  $this->jsondata(true, null, 'Destination has deleted successfuly', [], []);
    }
}

