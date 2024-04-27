<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Destination;
use App\Traits\DataFormController;
use Illuminate\Support\Facades\Validator;

class DestinationController extends Controller
{
    use DataFormController;

    public function get() {
        return $Destinations = Destination::latest()->get();
    }

    public function add(Request $request) {

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

        $crete_destination = Destination::create([
            "name_en" => $request->name_en,
            "name_ar" => $request->name_ar,
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

