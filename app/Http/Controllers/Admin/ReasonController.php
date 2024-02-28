<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Reason;
use App\Models\ReasonName;
use App\Traits\DataFormController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Traits\SavePhotoTrait;

class ReasonController extends Controller
{
    use DataFormController;
    use SavePhotoTrait;

    public function get() {
        return $currencies = Reason::with(["names"])->get();
    }

    public function add(Request $request) {
        $languages = Language::latest()->get();
        $keys = $languages->pluck('key')->all(); // get all Languages key as array

        // validate Hotel Names ---------------------------
        $missingNames = array_diff($keys, array_keys($request->names ? $request->names : [])); // compare keys with names keys to know whitch is missing

        if (!empty($missingNames)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Name in (' . Language::where('key', reset($missingNames))->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------

        $validator = Validator::make($request->all(), [
            'icon_path' => 'required',
        ], [
            'icon_path.required' => 'please enter reason Icon Path',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'add failed', [$validator->errors()->first()], []);
        }

        $image = $this->saveImg($request->icon_path, 'images/uploads/Reasons');
        $crete_reason = Reason::create([
            "icon_path" => '/images/uploads/Reasons/' . $image,
        ]);

        if ($crete_reason) :
            // Add Names
            foreach ($request->names as $lang => $name) {
                $addName = ReasonName::create([
                    'name' => $name,
                    'reason_id' => $crete_reason->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    
        endif;

        if ($crete_reason)
            return  $this->jsondata(true, null, 'Reason has added successfuly', [], []);
    }
    
    public function update(Request $request) {
        $languages = Language::latest()->get();
        $keys = $languages->pluck('key')->all(); // get all Languages key as array

        // validate Hotel Names ---------------------------
        $missingNames = array_diff($keys, array_keys($request->names ? $request->names : [])); // compare keys with names keys to know whitch is missing

        if (!empty($missingNames)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Reason Name in (' . Language::where('key', reset($missingNames))->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Hotel Description ---------------------------
        $missingDescriptions = array_diff($keys, array_keys($request->descriptions ? $request->descriptions : [])); // compare keys with names keys to know whitch is missing

        if (!empty($missingDescriptions)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Reason Descritpion in (' . Language::where('key', reset($missingNames))->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------

        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'delete failed', [$validator->errors()->first()], []);
        }

        $reason = Reason::find($request->id);

        if ($request->icon_path) {
            $image = $this->saveImg($request->icon_path, 'images/uploads/Reasons');
            $reason->icon_path = '/images/uploads/Reasons/' . $image;
        }
        $reason->names()->delete();

        if ($reason) :
            // Add Names
            foreach ($request->names as $lang => $name) {
                $addName = ReasonName::create([
                    'name' => $name,
                    'reason_id' => $reason->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    
            // Add Names
            foreach ($request->descriptions as $lang => $name) {
                $addName = DescriptionName::create([
                    'description' => $name,
                    'reason_id' => $reason->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    
        endif;

        $reason->save();

        if ($reason)
            return  $this->jsondata(true, null, 'Reason has updated successfuly', [], []);
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'delete failed', [$validator->errors()->first()], []);
        }

        $reason = Reason::find($request->id);
        $reason->names()->delete();
        $reason->delete();

        if ($reason)
            return  $this->jsondata(true, null, 'Reason has deleted successfuly', [], []);
    }
}
