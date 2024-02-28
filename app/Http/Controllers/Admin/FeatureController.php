<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Feature;
use App\Models\FeatureName;
use App\Traits\DataFormController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Traits\SavePhotoTrait;

class FeatureController extends Controller
{
    use DataFormController;
    use SavePhotoTrait;

    public function get() {
        return $features = Feature::with(["names"])->get();
    }

    public function add(Request $request) {
        $languages = Language::latest()->get();
        $keys = $languages->pluck('key')->all(); // get all Languages key as array

        // validate Hotel Names ---------------------------
        $missingNames = array_diff($keys, array_keys($request->names ? $request->names : [])); // compare keys with names keys to know whitch is missing

        if (!empty($missingNames)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Feature Name in (' . Language::where('key', reset($missingNames))->first()->name . ')'], []);
        }
        foreach ($request->names as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Feature Name in (' . Language::where('key', $key)->first()->name . ')'], []);
        }    
        // ----------------------------------------------------------------------------------------------------------------------

        $validator = Validator::make($request->all(), [
            'icon_path' => 'required',
        ], [
            'icon_path.required' => 'please enter feature Icon Path',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'add failed', [$validator->errors()->first()], []);
        }

        $image = $this->saveImg($request->icon_path, 'images/uploads/Features');
        $crete_feature = Feature::create([
            "icon_path" => '/images/uploads/Features/' . $image,
        ]);

        if ($crete_feature) :
            // Add Names
            foreach ($request->names as $lang => $name) {
                $addName = FeatureName::create([
                    'name' => $name,
                    'feature_id' => $crete_feature->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    
        endif;

        if ($crete_feature)
            return  $this->jsondata(true, null, 'Feature has added successfuly', [], []);
    }
    
    public function update(Request $request) {
        $languages = Language::latest()->get();
        $keys = $languages->pluck('key')->all(); // get all Languages key as array

        // validate Hotel Names ---------------------------
        $missingNames = array_diff($keys, array_keys($request->names ? $request->names : [])); // compare keys with names keys to know whitch is missing

        if (!empty($missingNames)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Name in (' . Language::where('key', reset($missingNames))->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------

        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'delete failed', [$validator->errors()->first()], []);
        }

        $feature = Feature::find($request->id);

        if ($request->icon_path) {
            $image = $this->saveImg($request->icon_path, 'images/uploads/Features');
            $feature->icon_path = '/images/uploads/Features/' . $image;
        }
        $feature->names()->delete();

        if ($feature) :
            // Add Names
            foreach ($request->names as $lang => $name) {
                $addName = FeatureName::create([
                    'name' => $name,
                    'feature_id' => $feature->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    
        endif;

        $feature->save();

        if ($feature)
            return  $this->jsondata(true, null, 'Feature has updated successfuly', [], []);
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'delete failed', [$validator->errors()->first()], []);
        }

        $feature = Feature::find($request->id);
        $feature->names()->delete();
        $feature->delete();

        if ($feature)
            return  $this->jsondata(true, null, 'Feature has deleted successfuly', [], []);
    }
}
