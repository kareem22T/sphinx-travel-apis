<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\DataFormController;
use App\Traits\SavePhotoTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\Resturant\Resturant;
use App\Models\Resturant\Title;
use App\Models\Resturant\Description;
use App\Models\Language;

class ResturantController extends Controller
{
    use DataFormController;
    use SavePhotoTrait;

    public function resturant(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Show failed', [$validator->errors()->first()], []);
        }

        $resturant = Resturant::with([
            "titles",
            "descriptions",
        ])->find($request->id);

        return $resturant;
    }

    public function get() {
        $lang_id = Language::where("key", "EN")->first() ? Language::where("key", "EN")->first()->id : (Language::first() ? Language::first()->id : '' );
        
        if ($lang_id) :
            return $resturants = Resturant::latest()->with(["titles"])->get();
        endif;
            
        return [];
    }

    public function create(Request $request) {
        $languages = Language::latest()->get();
        $keys = $languages->pluck('key')->all(); // get all Languages key as array

        // validate Resturant Titles ---------------------------
        $missingTitles = array_diff($keys, array_keys($request->titles ? $request->titles : [])); // compare keys with titles keys to know whitch is missing

        if (!empty($missingTitles)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Resturant Title in (' . Language::where('key', reset($missingTitles))->first()->name . ')'], []);
        }
        foreach ($request->titles as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Resturant Title in (' . Language::where('key', $key)->first()->name . ')'], []);
        }    
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Resturant Descriptions ---------------------------
        $missingDescriptions = array_diff($keys, array_keys($request->descriptions ? $request->descriptions : [])); // compare keys with descriptions keys to know whitch is missing

        if (!empty($missingDescriptions)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Resturant Description in (' . Language::where('key', reset($missingDescriptions))->first()->name . ')'], []);
        }
        foreach ($request->descriptions as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Resturant Description in (' . Language::where('key', $key)->first()->name . ')'], []);
        }    
        // ----------------------------------------------------------------------------------------------------------------------
        
        // then validate each single columns that does not need translation
        $validator = Validator::make($request->all(), [
            'thumbnail' => ['required'],
            'address' => ['required'],
            'lng' => ['required'],
            'lat' => ['required'],
            'addressName' => ['required'],
        ], [
            "address.required" => "Please enter Resturant address",
            "lat.required" => "Please enter Resturant address",
            "lng.required" => "Please enter Resturant address",
            "addressName.required" => "Please enter Resturant address",
        ]);
        
        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Create failed', [$validator->errors()->first()], []);
        }
        
        $image = $this->saveImg($request->thumbnail, 'images/uploads/Resturants');
        
        $resturant = Resturant::create([
            "thumbnail" => '/images/uploads/Resturants/' . $image,
            "address" => $request->address,
            "lat" => $request->lat,
            "lng" => $request->lng,
            "address_name" => $request->addressName
        ]);

        if ($resturant) :
            // Add Titles
            foreach ($request->titles as $lang => $title) {
                $addTitle = Title::create([
                    'title' => $title,
                    'resturant_id' => $resturant->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    

            // Add Descriptions
            foreach ($request->descriptions as $lang => $description) {
                $addDescription = Description::create([
                    'description' => $description,
                    'resturant_id' => $resturant->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    
            return $this->jsondata(true, null, "Resturant has Added successfuly", [], []);
        else:
            return $this->jsondata(false, null, 'Create failed', ["Failed to Create Resturant"], []);
        endif;



        return $request->excludes;
    }


    public function update(Request $request) {
        $languages = Language::latest()->get();
        $keys = $languages->pluck('key')->all(); // get all Languages key as array

        // validate Resturant Titles ---------------------------
        $missingTitles = array_diff($keys, array_keys($request->titles ? $request->titles : [])); // compare keys with titles keys to know whitch is missing

        if (!empty($missingTitles)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Resturant Title in (' . Language::where('key', reset($missingTitles))->first()->name . ')'], []);
        }
        foreach ($request->titles as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Resturant Title in (' . Language::where('key', $key)->first()->name . ')'], []);
        }    
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Resturant Descriptions ---------------------------
        $missingDescriptions = array_diff($keys, array_keys($request->descriptions ? $request->descriptions : [])); // compare keys with descriptions keys to know whitch is missing

        if (!empty($missingDescriptions)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Resturant Description in (' . Language::where('key', reset($missingDescriptions))->first()->name . ')'], []);
        }
        foreach ($request->descriptions as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Resturant Description in (' . Language::where('key', $key)->first()->name . ')'], []);
        }    
        // ----------------------------------------------------------------------------------------------------------------------
        
        // then validate each single columns that does not need translation
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'address' => ['required'],
            'lng' => ['required'],
            'lat' => ['required'],
            'addressName' => ['required'],
        ], [
            "address.required" => "Please enter Resturant address",
            "lat.required" => "Please enter Resturant address",
            "lng.required" => "Please enter Resturant address",
            "addressName.required" => "Please enter Resturant address",
        ]);
        
        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Create failed', [$validator->errors()->first()], []);
        }
        
        
        $resturant = Resturant::find($request->id);
        $resturant->address = $request->address;
        $resturant->lat = $request->lat;
        $resturant->lng = $request->lng;
        $resturant->address_name = $request->addressName;
        if ($request->thumbnail) {
            if ($resturant->thumbnail && file_exists(public_path($resturant->thumbnail))) {
                unlink(public_path($resturant->thumbnail));
            }
            $image = $this->saveImg($request->thumbnail, 'images/uploads/Resturants');
            $resturant->thumbnail = '/images/uploads/Resturants/' . $image;
        }
        $resturant->save();

        if ($resturant) :
            // Add Titles
            $resturant->titles()->delete();
            $resturant->descriptions()->delete();    
            foreach ($request->titles as $lang => $title) {
                $addTitle = Title::create([
                    'title' => $title,
                    'resturant_id' => $resturant->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    

            // Add Descriptions
            foreach ($request->descriptions as $lang => $description) {
                $addDescription = Description::create([
                    'description' => $description,
                    'resturant_id' => $resturant->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    
            return $this->jsondata(true, null, "Resturant has Updated successfuly", [], []);
        else:
            return $this->jsondata(false, null, 'Create failed', ["Failed to Create Resturant"], []);
        endif;



        return $request->excludes;
    }

    
    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'delete failed', [$validator->errors()->first()], []);
        }

        $resturant = Resturant::find($request->id);
        if ($resturant->thumbnail && file_exists(public_path($resturant->thumbnail))) {
            unlink(public_path($resturant->thumbnail));
        }
        // $image->delete();
        $resturant->titles()->delete();
        $resturant->descriptions()->delete();
        $resturant->delete();

        if ($resturant)
            return  $this->jsondata(true, null, 'Resturant has deleted successfuly', [], []);
    }

}
