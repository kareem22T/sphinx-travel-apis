<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Tour\Tour;
use App\Models\Tour\Title;
use App\Models\Tour\Intro;
use App\Models\Tour\Location;
use App\Models\Tour\Transportation;
use App\Models\Tour\IncludeModel;
use App\Models\Currency;
use App\Models\Tour\Exclude;
use App\Models\Tour\Gallery;
use App\Models\Tour\Day\Day;
use App\Models\Tour\Package\Package;
use App\Models\Tour\Day\Title as DayTitle;
use App\Models\Tour\Day\Description as DayDescription;
use App\Models\Tour\Package\Title as PackageTitle;
use App\Models\Tour\Package\Description as PackageDescription;
use App\Models\Tour\Package\Price as PackagePrice;
use App\Models\Tour\Package\Point;
use App\Models\Tour\Package\Point_titles as PintTitle;
use App\Models\Tour\Package\Point_descriptions as PointDescription;
use App\Traits\DataFormController;
use App\Traits\SavePhotoTrait;
use Illuminate\Support\Facades\Validator;

class TourController extends Controller
{
    use DataFormController;
    use SavePhotoTrait;
    public function get() {
        $lang_id = Language::where("key", "EN")->first() ? Language::where("key", "EN")->first()->id : (Language::first() ? Language::first()->id : '' );
        
        if ($lang_id) :
            $tours = Tour::latest()->with(["titles", "gallery"])->get();
            
            foreach ($tours as $tour) {
                if (isset($tour->gallery[0])) {
                    $tour->thumbnail = $tour->gallery[0]->path;
                } else {
                    $tour->thumbnail = null; // or whatever default value you prefer
                }
            }
            return $tours;
        endif;
            
        return [];
    }

    public function create(Request $request) {
        $languages = Language::latest()->get();
        $keys = $languages->pluck('key')->all(); // get all Languages key as array

        $currencies = Currency::latest()->get();
        $codes = $currencies->pluck('id')->all(); // get all Languages key as array

        // validate Tour Titles ---------------------------
        $missingTitles = array_diff($keys, array_keys($request->titles ? $request->titles : [])); // compare keys with titles keys to know whitch is missing

        if (!empty($missingTitles)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Tour Title in (' . Language::where('key', reset($missingTitles))->first()->name . ')'], []);
        }
        foreach ($request->titles as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Tour Title in (' . Language::where('key', $key)->first()->name . ')'], []);
        }    
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Tour Intros ---------------------------
        $missingIntros = array_diff($keys, array_keys($request->intros ? $request->intros : [])); // compare keys with intros keys to know whitch is missing

        if (!empty($missingIntros)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Tour Intro in (' . Language::where('key', reset($missingIntros))->first()->name . ')'], []);
        }
        foreach ($request->intros as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Tour Intro in (' . Language::where('key', $key)->first()->name . ')'], []);
        }    
        // ----------------------------------------------------------------------------------------------------------------------


        // validate Tour Locations ---------------------------
        $missingLocations = array_diff($keys, array_keys($request->locations ? $request->locations : [])); // compare keys with locations keys to know whitch is missing

        if (!empty($missingLocations)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Tour Location in (' . Language::where('key', reset($missingLocations))->first()->name . ')'], []);
        }
        foreach ($request->locations as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Tour Location in (' . Language::where('key', $key)->first()->name . ')'], []);
        }    
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Tour Transportations ---------------------------
        $missingTransportations = array_diff($keys, array_keys($request->transportations ? $request->transportations : [])); // compare keys with transportations keys to know whitch is missing

        if (!empty($missingTransportations)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Tour Transportation in (' . Language::where('key', reset($missingTransportations))->first()->name . ')'], []);
        }
        foreach ($request->transportations as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Tour Transportation in (' . Language::where('key', $key)->first()->name . ')'], []);
        }    
        // ----------------------------------------------------------------------------------------------------------------------

        
        // then validate each single columns that does not need translation
        $validator = Validator::make($request->all(), [
            'expired_date' => ['required'],
            'duration' => ['required'],
            'min_participant' => ['required'],
            'max_participant' => ['required'],
        ], [
            "expired_date.required" => "Please enter Tour expired_date",
            "duration.required" => "Please enter Tour Duration in days",
            "min_participant.required" => "Please enter Tour Min participants",
            "max_participant.required" => "Please enter Tour Max participants",
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Create failed', [$validator->errors()->first()], []);
        }

        
        // validate Tour Includes ---------------------------
        $missingIncludes = array_diff($keys, array_keys($request->includes ? $request->includes : [])); // compare keys with includes keys to know whitch is missing

        if (!empty($missingIncludes)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Tour Includes in (' . Language::where('key', reset($missingIncludes))->first()->name . ')'], []);
        }
        foreach ($request->includes as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Tour Includes in (' . Language::where('key', $key)->first()->name . ')'], []);
        }    
        // ----------------------------------------------------------------------------------------------------------------------
        
        // validate Tour Excludes ---------------------------
        $missingExcludes = array_diff($keys, array_keys($request->excludes ? $request->excludes : [])); // compare keys with excludes keys to know whitch is missing

        if (!empty($missingExcludes)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Tour Excludes in (' . Language::where('key', reset($missingExcludes))->first()->name . ')'], []);
        }
        foreach ($request->excludes as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Tour Excludes in (' . Language::where('key', $key)->first()->name . ')'], []);
        }    
        // ----------------------------------------------------------------------------------------------------------------------

        if (count($request->gallery ? $request->gallery : []) < 5) {
            return $this->jsondata(false, null, 'Update failed', ["You have to choose at least 5 images for gallary"], []);
        }

        if (count($request->days ? $request->days : []) === 0)
            return $this->jsondata(false, null, 'Add failed', ['Please Enter Days data'], []);

        foreach ($request->days as $num => $day) {
            if (!isset($day['thumbnail']) || !$day['thumbnail'])
                return $this->jsondata(false, null, 'Add failed', ['Please Choose day ' . $num . " Thumbnail"], []);

            if (!isset($day['titles']) ||!$day['titles'])
                return $this->jsondata(false, null, 'Add failed', ['Please enter day ' . $num . " titles"], []);


            // validate Day titles ---------------------------
            $missingDayTitles = array_diff($keys, array_keys($day['titles'] ? $day['titles'] : []));

            if (!empty($missingDayTitles)) {  // If is there missing keys so show msg to admin with this language
                return $this->jsondata(false, null, 'Add failed', ['Please enter Day ' . $num . ' Title in (' . Language::where('key', reset($missingDayTitles))->first()->name . ')'], []);
            }
            foreach ($day['titles'] as $key => $value) {
                if (!$value)
                    return $this->jsondata(false, null, 'Add failed', ['Please enter Day ' . $num . ' Title in (' . Language::where('key', $key)->first()->name . ')'], []);
            }    
            // ----------------------------------------------------------------------------------------------------------------------

            if (!isset($day['descriptions']) ||!$day['descriptions'])
                return $this->jsondata(false, null, 'Add failed', ['Please enter day ' . $num . " titles"], []);


            // validate Day Descriptions ---------------------------
            $missingDayDescriptions = array_diff($keys, array_keys($day['descriptions'] ? $day['descriptions'] : [])); 

            if (!empty($missingDayDescriptions)) {  // If is there missing keys so show msg to admin with this language
                return $this->jsondata(false, null, 'Add failed', ['Please enter Day ' . $num . ' Description in (' . Language::where('key', reset($missingDayDescriptions))->first()->name . ')'], []);
            }
            foreach ($day['descriptions'] as $key => $value) {
                if (!$value)
                    return $this->jsondata(false, null, 'Add failed', ['Please enter Day ' . $num . ' Description in (' . Language::where('key', $key)->first()->name . ')'], []);
            }    
            // ----------------------------------------------------------------------------------------------------------------------
        }

        if (!isset($request['packages']) || count($request['packages']) < 1)
            return $this->jsondata(false, null, 'Add failed', ['Please enter packages data'], []);

        foreach ($request['packages'] as $num => $package) {

            if (!isset($package['titles']) ||!$package['titles'])
                return $this->jsondata(false, null, 'Add failed', ['Please enter package ' . $num . " titles"], []);


            // validate package titles ---------------------------
            $missingpackageTitles = array_diff($keys, array_keys($package['titles'] ? $package['titles'] : []));

            if (!empty($missingpackageTitles)) {  // If is there missing keys so show msg to admin with this language
                return $this->jsondata(false, null, 'Add failed', ['Please enter package ' . $num . ' Title in (' . Language::where('key', reset($missingpackageTitles))->first()->name . ')'], []);
            }
            foreach ($package['titles'] as $key => $value) {
                if (!$value)
                    return $this->jsondata(false, null, 'Add failed', ['Please enter package ' . $num . ' Title in (' . Language::where('key', $key)->first()->name . ')'], []);
            }    
            // ----------------------------------------------------------------------------------------------------------------------

            if (!isset($package['descriptions']) ||!$package['descriptions'])
                return $this->jsondata(false, null, 'Add failed', ['Please enter package ' . $num . " descriptions"], []);


            // validate package Descriptions ---------------------------
            $missingpackageDescriptions = array_diff($keys, array_keys($package['descriptions'] ? $package['descriptions'] : [])); 

            if (!empty($missingpackageDescriptions)) {  // If is there missing keys so show msg to admin with this language
                return $this->jsondata(false, null, 'Add failed', ['Please enter package ' . $num . ' Description in (' . Language::where('key', reset($missingpackageDescriptions))->first()->name . ')'], []);
            }
            foreach ($package['descriptions'] as $key => $value) {
                if (!$value)
                    return $this->jsondata(false, null, 'Add failed', ['Please enter package ' . $num . ' Description in (' . Language::where('key', $key)->first()->name . ')'], []);
            }    
            // ----------------------------------------------------------------------------------------------------------------------

            if (isset($package['features']) && count($package['features']))
            {
                foreach ($package['features'] as $indexF => $feature) {
                    if (!isset($feature['descriptions']) ||!$feature['descriptions'])
                        return $this->jsondata(false, null, 'Add failed', ['Please enter package feature ' . $indexF . " descriptions"], []);
    
                    if (!isset($feature['names']) ||!$feature['names'])
                        return $this->jsondata(false, null, 'Add failed', ['Please enter package feature ' . $indexF . " names"], []);
    
    
                    if (!$value)
                        return $this->jsondata(false, null, 'Add failed', ['Please enter package ' . $num . ' Description in (' . Language::where('key', $key)->first()->name . ')'], []);
                }    
    
            }

            if (!isset($package['prices']) ||!$package['prices'])
                return $this->jsondata(false, null, 'Add failed', ['Please enter package ' . $num . " prices"], []);


            // validate Room Prices ---------------------------
            $missingPrices = array_diff($codes, array_keys($package['prices'] ? $package['prices'] : [])); // compare keys with description keys to know whitch is missing

            if (!empty($missingPrices)) {  // If is there missing keys so show msg to admin with this language
                return $this->jsondata(false, null, 'Add failed', ['Please enter package ' . $num . ' Price in (' . Currency::where('id', reset($missingPrices))->first()->names[0]->name . ')'], []);
            }

            foreach ($package['prices'] as $key => $value) {
                if (!$value)
                    return $this->jsondata(false, null, 'Add failed', ['Please enter package ' . $num . ' Price in (' . Currency::where('id', $key)->first()->names[0]->name . ')'], []);
            }
            // ----------------------------------------------------------------------------------------------------------------------

        }
        
        $tour = Tour::create([
            "expired_date" => $request->expired_date,
            "duration" => $request->duration,
            "min_participant" => $request->min_participant,
            "max_participant" => $request->max_participant
        ]);

        if ($tour) :
            // Add Titles
            foreach ($request->titles as $lang => $title) {
                $addTitle = Title::create([
                    'title' => $title,
                    'tour_id' => $tour->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    

            // Add Intros
            foreach ($request->intros as $lang => $intro) {
                $addIntro = Intro::create([
                    'intro' => $intro,
                    'tour_id' => $tour->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    

            // Add Locations
            foreach ($request->locations as $lang => $location) {
                $addLocation = Location::create([
                    'location' => $location,
                    'tour_id' => $tour->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    

            // Add Transportations
            foreach ($request->transportations as $lang => $transportation) {
                $addTransportation = Transportation::create([
                    'transportation' => $transportation,
                    'tour_id' => $tour->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    

            // Add Includes
            foreach ($request->includes as $lang => $include) {
                $addInclude = IncludeModel::create([
                    'include' => $include,
                    'tour_id' => $tour->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    

            // Add Exclude
            foreach ($request->excludes as $lang => $exclude) {
                $addExclude = Exclude::create([
                    'exclude' => $exclude,
                    'tour_id' => $tour->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    

            // Add Gallaray images
            foreach ($request->gallery as $img) {
                $image = $this->saveImg($img, 'images/uploads/Tours/tour_' . $tour->id);
                if ($image)
                    $upload_image = Gallery::create([
                        'path' => '/images/uploads/Tours/tour_' . $tour->id . '/' . $image,
                        'tour_id' => $tour->id,
                    ]);
            }
            
            // add days
            foreach ($request->days as $index => $day) {
                $image = $this->saveImg($day['thumbnail'], 'images/uploads/Tours/tour_' . $tour->id . '/days/day_' . $index);

                $addDay = Day::create([
                    "thumbnail" => 'images/uploads/Tours/tour_' . $tour->id . '/days/day_' . $index . '/' . $image,
                    "tour_id" => $tour->id
                ]);

                //add day titles
                    foreach ($day['titles'] as $lang => $title) {
                        $addTitle = DayTitle::create([
                            'title' => $title,
                            'day_id' => $addDay->id,
                            'language_id' => Language::where('key', $lang)->first()->id,
                        ]);
                    };    

                //add day descriptions
                    foreach ($day['descriptions'] as $lang => $description) {
                        $addDescription = DayDescription::create([
                            'description' => $description,
                            'day_id' => $addDay->id,
                            'language_id' => Language::where('key', $lang)->first()->id,
                        ]);
                    };    
            }
            
            // add packages
            foreach ($request->packages as $index => $package) {
                $addpackage = Package::create([
                    "tour_id" => $tour->id
                ]);

                //add package titles
                    foreach ($package['titles'] as $lang => $title) {
                        $addTitle = PackageTitle::create([
                            'title' => $title,
                            'package_id' => $addpackage->id,
                            'language_id' => Language::where('key', $lang)->first()->id,
                        ]);
                    };    

                //add package descriptions
                    foreach ($package['descriptions'] as $lang => $description) {
                        $addDescription = PackageDescription::create([
                            'description' => $description,
                            'package_id' => $addpackage->id,
                            'language_id' => Language::where('key', $lang)->first()->id,
                        ]);
                    };
                    
                // Add Prices
                foreach ($package['prices'] as $currency => $prices) {
                    $addPrices = PackagePrice::create([
                        'price' => $prices,
                        'package_id' => $addpackage->id,
                        'currency_id' => Currency::where('id', $currency)->first()->id,
                    ]);
                }; 

                if (isset($package['features']))
                    foreach ($package['features'] as $point) {
                        $addPoint = Point::create([
                            "package_id" => $addpackage->id
                        ]);
        
                        //add Point titles
                            foreach ($point['names'] as $lang => $title) {
                                $addTitle = PintTitle::create([
                                    'title' => $title,
                                    'point_id' => $addPoint->id,
                                    'language_id' => Language::where('key', $lang)->first()->id,
                                ]);
                            };    
        
                        //add point descriptions
                            foreach ($point['descriptions'] as $lang => $description) {
                                $addDescription = PointDescription::create([
                                    'description' => $description,
                                    'point_id' => $addPoint->id,
                                    'language_id' => Language::where('key', $lang)->first()->id,
                                ]);
                            };        
                    }

            }
            return $this->jsondata(true, null, "Tour has Added successfuly", [], []);
        else:
            return $this->jsondata(false, null, 'Create failed', ["Failed to Create tour"], []);
        endif;



        return $request->excludes;
    }
}
