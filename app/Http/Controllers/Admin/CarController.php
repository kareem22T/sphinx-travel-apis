<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\DataFormController;
use App\Traits\SavePhotoTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\Car\Car;
use App\Models\Car\Title;
use App\Models\Car\Description;
use App\Models\Car\Type;
use App\Models\Car\Price;
use App\Models\Car\Gallery;
use App\Models\CarFeature as Feature;
use App\Models\CarFeatureName as FeatureName;
use App\Models\Language;
use App\Models\Currency;

class CarController extends Controller
{
    use DataFormController;
    use SavePhotoTrait;
    public function get() {
        $lang_id = Language::where("key", "EN")->first() ? Language::where("key", "EN")->first()->id : (Language::first() ? Language::first()->id : '' );
        
        if ($lang_id) :
            $cars = Car::latest()->with(["titles", "gallery"])->get();
            
            foreach ($cars as $car) {
                if (isset($car->gallery[0])) {
                    $car->thumbnail = $car->gallery[0]->path;
                } else {
                    $car->thumbnail = null; // or whatever default value you prefer
                }
            }
            return $cars;
        endif;
            
        return [];
    }

    public function create(Request $request) {
        $languages = Language::latest()->get();
        $keys = $languages->pluck('key')->all(); // get all Languages key as array
        $currencies = Currency::latest()->get();
        $codes = $currencies->pluck('id')->all(); // get all Languages key as array

        // validate Car Titles ---------------------------
        $missingTitles = array_diff($keys, array_keys($request->titles ? $request->titles : [])); // compare keys with titles keys to know whitch is missing

        if (!empty($missingTitles)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Car Title in (' . Language::where('key', reset($missingTitles))->first()->name . ')'], []);
        }
        foreach ($request->titles as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Car Title in (' . Language::where('key', $key)->first()->name . ')'], []);
        }    
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Car Types ---------------------------
        $missingTypes = array_diff($keys, array_keys($request->types ? $request->types : [])); // compare keys with types keys to know whitch is missing

        if (!empty($missingTypes)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Car Type in (' . Language::where('key', reset($missingTypes))->first()->name . ')'], []);
        }
        foreach ($request->types as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Car Type in (' . Language::where('key', $key)->first()->name . ')'], []);
        }    
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Car Descriptions ---------------------------
        $missingDescriptions = array_diff($keys, array_keys($request->descriptions ? $request->descriptions : [])); // compare keys with descriptions keys to know whitch is missing

        if (!empty($missingDescriptions)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Car Description in (' . Language::where('key', reset($missingDescriptions))->first()->name . ')'], []);
        }
        foreach ($request->descriptions as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Car Description in (' . Language::where('key', $key)->first()->name . ')'], []);
        }    
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Car Prices ---------------------------
        $missingPrices = array_diff($codes, array_keys($request->prices ? $request->prices : [])); // compare keys with description keys to know whitch is missing

        if (!empty($missingPrices)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Car Price in (' . Currency::where('id', reset($missingPrices))->first()->names[0]->name . ')'], []);
        }

        foreach ($request->prices as $key => $value) {
            if (!$value)
            return $this->jsondata(false, null, 'Add failed', ['Please enter Car Price in (' . Currency::where('id', $key)->first()->names[0]->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------


        // then validate each single columns that does not need translation
        $validator = Validator::make($request->all(), [
            'phone' => ['required'],
            'address' => ['required'],
            'lng' => ['required'],
            'lat' => ['required'],
            'addressName' => ['required'],
        ], [
            "address.required" => "Please enter Car address",
            "lat.required" => "Please enter Car address",
            "lng.required" => "Please enter Car address",
            "addressName.required" => "Please enter Car address",
        ]);
        
        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Create failed', [$validator->errors()->first()], []);
        }
        
        if (count($request->features ? $request->features : []) < 5) {
            return $this->jsondata(false, null, 'Update failed', ["You have to choose at least 5 Features"], []);
        }

        if (count($request->gallery ? $request->gallery : []) < 3) {
            return $this->jsondata(false, null, 'Update failed', ["You have to choose at least 3 images"], []);
        }

        $car = Car::create([
            "address" => $request->address,
            "phone" => $request->phone,
            "lat" => $request->lat,
            "lng" => $request->lng,
            "address_name" => $request->addressName
        ]);

        if ($car) :
            // Add Titles
            foreach ($request->titles as $lang => $title) {
                $addTitle = Title::create([
                    'title' => $title,
                    'car_id' => $car->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    

            // Add Descriptions
            foreach ($request->descriptions as $lang => $description) {
                $addDescription = Description::create([
                    'description' => $description,
                    'car_id' => $car->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    
            // Add Type
            foreach ($request->types as $lang => $type) {
                $addType = Type::create([
                    'type' => $type,
                    'car_id' => $car->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    

            // Add Prices
            foreach ($request->prices as $currency => $prices) {
                $addPrices = Price::create([
                    'price' => $prices,
                    'car_id' => $car->id,
                    'currency_id' => Currency::where('id', $currency)->first()->id,
                ]);
            };      
            
            // Add Gallaray images
            foreach ($request->gallery as $img) {
                $image = $this->saveImg($img, 'images/uploads/Cars/car_' . $car->id);
                if ($image)
                    $upload_image = Gallery::create([
                        'path' => '/images/uploads/Cars/car_' . $car->id . '/' . $image,
                        'car_id' => $car->id,
                    ]);
            }        
            // add car features
            foreach ($request->features as $feature) {
                $car->features()->attach([$feature['id']]);
            }    
        
            return $this->jsondata(true, null, "Car has Added successfuly", [], []);
        else:
            return $this->jsondata(false, null, 'Create failed', ["Failed to Create Car"], []);
        endif;



        return $request->excludes;
    }

    public function getFeatures() {
        return $features = Feature::with(["names"])->get();
    }

    public function addFeature(Request $request) {
        $languages = Language::latest()->get();
        $keys = $languages->pluck('key')->all(); // get all Languages key as array

        // validate Car Names ---------------------------
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

        $image = $this->saveImg($request->icon_path, 'images/uploads/Cars/Features');
        $crete_feature = Feature::create([
            "icon_path" => '/images/uploads/Cars/Features/' . $image,
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
    public function updateFeature(Request $request) {
        $languages = Language::latest()->get();
        $keys = $languages->pluck('key')->all(); // get all Languages key as array

        // validate Car Names ---------------------------
        $missingNames = array_diff($keys, array_keys($request->names ? $request->names : [])); // compare keys with names keys to know whitch is missing

        if (!empty($missingNames)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Car Name in (' . Language::where('key', reset($missingNames))->first()->name . ')'], []);
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

    public function deleteFeature(Request $request) {
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
