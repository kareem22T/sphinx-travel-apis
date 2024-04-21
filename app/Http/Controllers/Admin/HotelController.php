<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel\Hotel;
use App\Models\Hotel\Slogan;
use App\Models\Hotel\Name;
use App\Models\Hotel\Description;
use App\Models\Hotel\Gallery;
use App\Models\Hotel\Address;
use App\Models\Hotel\Rooms\Name as RoomName;
use App\Models\Hotel\Rooms\Description as RoomDescription;
use App\Models\Hotel\Rooms\Gallery as RoomGallery;
use App\Models\Hotel\Rooms\Price as RoomPrice;
use App\Models\Language;
use App\Models\Reason;
use App\Models\Feature;
use App\Models\ReasonName;
use App\Models\ReasonDescription;
use App\Models\Currency;
use App\Models\Hotel\Rooms\Room;
use App\Traits\DataFormController;
use App\Traits\SavePhotoTrait;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
    use DataFormController;
    use SavePhotoTrait;

    public function get() {
        $lang_id = Language::where("key", "EN")->first() ? Language::where("key", "EN")->first()->id : (Language::first() ? Language::first()->id : '' );

        if ($lang_id) :
            $hotels = Hotel::latest()->with(["names", "gallery"])->get();

            foreach ($hotels as $hotel) {
                if (isset($hotel->gallery[0])) {
                    $hotel->thumbnail = $hotel->gallery[0]->path;
                } else {
                    $hotel->thumbnail = null; // or whatever default value you prefer
                }
            }
            return $hotels;
        endif;

        return [];
    }

    public function create(Request $request) {
        $languages = Language::latest()->get();
        $keys = $languages->pluck('key')->all(); // get all Languages key as array

        // validate Hotel Names ---------------------------
        $missingNames = array_diff($keys, array_keys($request->names ? $request->names : [])); // compare keys with names keys to know whitch is missing

        if (!empty($missingNames)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Name in (' . Language::where('key', reset($missingNames))->first()->name . ')'], []);
        }
        foreach ($request->names as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Name in (' . Language::where('key', $key)->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Hotel Slogans ---------------------------
        if ($request->slogans && count($request->slogans) > 0) : // here slogans are not required but if he addedd one have to translate it (:
            $missingSlogans = array_diff($keys, array_keys($request->slogans ? $request->slogans : [])); // compare keys with names keys to know whitch is missing

            if (!empty($missingSlogans)) {  // If is there missing keys so show msg to admin with this language
                return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Slogan in (' . Language::where('key', reset($missingSlogans))->first()->name . ')'], []);
            }
        endif;
        foreach ($request->slogans as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Slogan in (' . Language::where('key', $key)->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Hotel Descriptions ---------------------------
        $missingDescriptions = array_diff($keys, array_keys($request->descriptions ? $request->descriptions : [])); // compare keys with description keys to know whitch is missing

        if (!empty($missingDescriptions)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Description in (' . Language::where('key', reset($missingDescriptions))->first()->name . ')'], []);
        }
        foreach ($request->descriptions as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Description in (' . Language::where('key', $key)->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Hotel Addresses ---------------------------
        $missingAddresses = array_diff($keys, array_keys($request->addresses ? $request->addresses : [])); // compare keys with address keys to know whitch is missing

        if (!empty($missingAddresses)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Address in (' . Language::where('key', reset($missingAddresses))->first()->name . ')'], []);
        }
        foreach ($request->addresses as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Address in (' . Language::where('key', $key)->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------


        // then validate each single columns that does not need translation
        $validator = Validator::make($request->all(), [
            'phone' => ['required'],
            'check_in' => "required",
            'check_out' => "required",
            'address' => ['required'],
            'type' => ['required'],
            'lng' => ['required'],
            'lat' => ['required'],
            'addressName' => ['required'],
        ], [
            "phone.required" => "Please enter Hotel Customer service Number",
            "phone.regex" => "Please enter Customer service Valid Number",
            "map.required" => "Please enter hotel map iframe",
            "check_in.required" => "Please enter hotel Check In time",
            "check_in.check_out" => "Please enter hotel Check Out time",
            "address.required" => "Please enter hotel address",
            "lat.required" => "Please enter hotel address",
            "lng.required" => "Please enter hotel address",
            "addressName.required" => "Please enter hotel address",
            "type.required" => "Please choose hotel type",
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Create failed', [$validator->errors()->first()], []);
        }

        if (count($request->features ? $request->features : []) < 5) {
            return $this->jsondata(false, null, 'Update failed', ["You have to choose at least 5 Features"], []);
        }

        if (count($request->reasons ? $request->reasons : []) < 3) {
            return $this->jsondata(false, null, 'Update failed', ["You have to choose at least 3 reasons"], []);
        }

        if (count($request->gallery ? $request->gallery : []) < 5) {
            return $this->jsondata(false, null, 'Update failed', ["You have to choose at least 5 images"], []);
        }
        $create_hotel = Hotel::create([ // If Validation Pass so create the hotel
            "phone" => $request->phone,
            "check_in" => $request->check_in,
            "check_out" => $request->check_out,
            "type" => $request->type,
            "address" => $request->address,
            "lat" => $request->lat,
            "lng" => $request->lng,
            "address_name" => $request->addressName
        ]);

        if ($create_hotel) :
            // Add Names
            foreach ($request->names as $lang => $name) {
                $addName = Name::create([
                    'name' => $name,
                    'hotel_id' => $create_hotel->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };
            // Add Slogan
            if ($request->slogans && count($request->slogans) > 0)
                foreach ($request->slogans as $lang => $slogan) {
                    $addSlogan = Slogan::create([
                        'slogan' => $slogan,
                        'hotel_id' => $create_hotel->id,
                        'language_id' => Language::where('key', $lang)->first()->id,
                    ]);
                };
            // Add Descriptions
                foreach ($request->descriptions as $lang => $description) {
                    $addDescripiton = Description::create([
                        'description' => $description,
                        'hotel_id' => $create_hotel->id,
                        'language_id' => Language::where('key', $lang)->first()->id,
                    ]);
                };
            // Add Addresses
                foreach ($request->addresses as $lang => $address) {
                    $addAddresses = Address::create([
                        'address' => $address,
                        'hotel_id' => $create_hotel->id,
                        'language_id' => Language::where('key', $lang)->first()->id,
                    ]);
                };
            // Add Gallaray images
                foreach ($request->gallery as $img) {
                    $image = $this->saveImg($img, 'images/uploads/Hotels/hotel_' . $create_hotel->id);
                    if ($image)
                        $upload_image = Gallery::create([
                            'path' => '/images/uploads/Hotels/hotel_' . $create_hotel->id . '/' . $image,
                            'hotel_id' => $create_hotel->id,
                        ]);
                }
            // add hotel features
                foreach ($request->features as $feature) {
                    $create_hotel->features()->attach([$feature['id']]);
                }

                if ($request->tours)
                    foreach ($request->tours as $tour) {
                        $create_hotel->tours()->attach([$tour['id']]);
                    }


            // add hotel reasons
                foreach ($request->reasons as $reason) {
                    $image = $this->saveImg($reason['thumbnail'], 'images/uploads/Reasons');

                    $create_reason = Reason::create([
                        "icon_path" => '/images/uploads/Reasons/' . $image,
                        "hotel_id" => $create_hotel->id,
                    ]);
                    // Add Descriptions
                    foreach ($reason['descriptions'] as $lang => $description) {
                        $addDescripitonreason = ReasonDescription::create([
                            'description' => $description,
                            'reason_id' => $create_reason['id'],
                            'language_id' => Language::where('key', $lang)->first()->id,
                        ]);
                    };

                    // Add Descriptions
                    foreach ($reason['names'] as $lang => $name) {
                        $addName = ReasonName::create([
                            'name' => $name,
                            'reason_id' => $create_reason->id,
                            'language_id' => Language::where('key', $lang)->first()->id,
                        ]);
                    };

                }

            return $this->jsondata(true, null, "Hotel has Added successfuly", [], []);
        else:
            return $this->jsondata(false, null, 'Create failed', ["Failed to Create hotel"], []);
        endif;
    }

    public function update(Request $request) {
        $languages = Language::latest()->get();
        $keys = $languages->pluck('key')->all(); // get all Languages key as array

        // validate Hotel Names ---------------------------
        $missingNames = array_diff($keys, array_keys($request->names ? $request->names : [])); // compare keys with names keys to know whitch is missing

        if (!empty($missingNames)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Name in (' . Language::where('key', reset($missingNames))->first()->name . ')'], []);
        }
        foreach ($request->names as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Name in (' . Language::where('key', $key)->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Hotel Slogans ---------------------------
        if ($request->slogans && count($request->slogans) > 0) : // here slogans are not required but if he addedd one have to translate it (:
            $missingSlogans = array_diff($keys, array_keys($request->slogans ? $request->slogans : [])); // compare keys with names keys to know whitch is missing

            if (!empty($missingSlogans)) {  // If is there missing keys so show msg to admin with this language
                return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Slogan in (' . Language::where('key', reset($missingSlogans))->first()->name . ')'], []);
            }
        endif;
        foreach ($request->slogans as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Slogan in (' . Language::where('key', $key)->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Hotel Descriptions ---------------------------
        $missingDescriptions = array_diff($keys, array_keys($request->descriptions ? $request->descriptions : [])); // compare keys with description keys to know whitch is missing

        if (!empty($missingDescriptions)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Description in (' . Language::where('key', reset($missingDescriptions))->first()->name . ')'], []);
        }
        foreach ($request->descriptions as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Description in (' . Language::where('key', $key)->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Hotel Addresses ---------------------------
        $missingAddresses = array_diff($keys, array_keys($request->addresses ? $request->addresses : [])); // compare keys with address keys to know whitch is missing

        if (!empty($missingAddresses)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Address in (' . Language::where('key', reset($missingAddresses))->first()->name . ')'], []);
        }
        foreach ($request->addresses as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Hotel Address in (' . Language::where('key', $key)->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------


        // then validate each single columns that does not need translation
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'phone' => ['required'],
            'check_in' => "required",
            'check_out' => "required",
            'address' => ['required'],
            'type' => ['required'],
            'lng' => ['required'],
            'lat' => ['required'],
            'addressName' => ['required'],
        ], [
            "phone.required" => "Please enter Hotel Customer service Number",
            "phone.regex" => "Please enter Customer service Valid Number",
            "map.required" => "Please enter hotel map iframe",
            "check_in.required" => "Please enter hotel Check In time",
            "check_in.check_out" => "Please enter hotel Check Out time",
            "address.required" => "Please enter hotel address",
            "lat.required" => "Please enter hotel address",
            "lng.required" => "Please enter hotel address",
            "addressName.required" => "Please enter hotel address",
            "type.required" => "Please choose hotel type",
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Update failed', [$validator->errors()->first()], []);
        }

        if (count($request->features ? $request->features : []) < 5) {
            return $this->jsondata(false, null, 'Update failed', ["You have to choose at least 5 features"], []);
        }

        if (count($request->oldReasons ? $request->oldReasons : []) + count($request->reasons ? $request->reasons : []) < 3) {
            return $this->jsondata(false, null, 'Update failed', ["You have to choose at least 3 reasons"], []);
        }

        if (count($request->oldGallery ? $request->oldGallery : []) + count($request->gallery ? $request->gallery : []) < 5) {
            return $this->jsondata(false, null, 'Update failed', ["You have to choose at least 5 Images"], []);
        }

        $hotel = Hotel::find($request->id);
        $hotel->check_in = $request->check_in;
        $hotel->check_out = $request->check_out;
        $hotel->phone = $request->phone;
        $hotel->address = $request->address;
        $hotel->lat = $request->lat;
        $hotel->lng = $request->lng;
        $hotel->type = $request->type;
        $hotel->address_name = $request->addressName;
        $hotel->save();
        if ($hotel) :
            $hotel->names()->delete();
            // Add Names
            foreach ($request->names as $lang => $name) {
                $addName = Name::create([
                    'name' => $name,
                    'hotel_id' => $hotel->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };

            $hotel->slogans()->delete();
            // Add Slogan
            if ($request->slogans && count($request->slogans) > 0)
                foreach ($request->slogans as $lang => $slogan) {
                    $addSlogan = Slogan::create([
                        'slogan' => $slogan,
                        'hotel_id' => $hotel->id,
                        'language_id' => Language::where('key', $lang)->first()->id,
                    ]);
                };

            $hotel->descriptions()->delete();
            // Add Descriptions
                foreach ($request->descriptions as $lang => $description) {
                    $addDescripiton = Description::create([
                        'description' => $description,
                        'hotel_id' => $hotel->id,
                        'language_id' => Language::where('key', $lang)->first()->id,
                    ]);
                };

            $hotel->addresses()->delete();
            // Add Addresses
                foreach ($request->addresses as $lang => $address) {
                    $addAddresses = Address::create([
                        'address' => $address,
                        'hotel_id' => $hotel->id,
                        'language_id' => Language::where('key', $lang)->first()->id,
                    ]);
                };


            $oldGalleryIds = [];
            if ($request->oldGallery)
            foreach ($request->oldGallery as $img) {
                $oldGalleryIds[] = $img['id'];
            }

            // Fetch Gallery where hotel_id is 3 and id is not in the old gallery IDs
            $removedGallery = Gallery::where('hotel_id', $request->id)
            ->whereNotIn('id', $oldGalleryIds)
            ->get();

            // Remove images from the filesystem for the new gallery
            foreach ($removedGallery as $image) {
                if (file_exists(public_path($image->path))) {
                    unlink(public_path($image->path));
                }
                $image->delete();
            }

            if ($request->gallery)
            // Add Gallaray images
                foreach ($request->gallery as $img) {
                    $image = $this->saveImg($img, 'images/uploads/Hotels/hotel_' . $hotel->id);
                    if ($image)
                        $upload_image = Gallery::create([
                            'path' => '/images/uploads/Hotels/hotel_' . $hotel->id . '/' . $image,
                            'hotel_id' => $hotel->id,
                        ]);
                }

            $hotel->features()->detach();
            // add hotel features
            foreach ($request->features as $feature) {
                $hotel->features()->attach([$feature['id']]);
            }
            if ($request->tours) {

                $hotel->tours()->detach();
                // add hotel features
                foreach ($request->tours as $tour) {
                    $hotel->tours()->attach([$tour['id']]);
                }


            }

            $oldReasonsIds = [];
            if($request->oldReasons)
            foreach ($request->oldReasons as $reason) {
                $oldReasonsIds[] = $reason['id'];
            }

            // Fetch Gallery where hotel_id and id is not in the old gallery IDs
            $removedReasons = Reason::with("names", "descriptions")->where('hotel_id', $request->id)
            ->whereNotIn('id', $oldReasonsIds)
            ->get();

            // Remove images from the filesystem for the new gallery
            foreach ($removedReasons as $reason) {
                if (file_exists($reason->icon_path)) {
                    unlink($reason->icon_path);
                }
                $reason->names()->delete();
                $reason->descriptions()->delete();
                $reason->delete();
            }

            // add hotel reasons
            if ($request->reasons)
            foreach ($request->reasons as $reason) {
                $image = $this->saveImg($reason['thumbnail'], 'images/uploads/Reasons');

                $create_reason = Reason::create([
                    "icon_path" => '/images/uploads/Reasons/' . $image,
                    "hotel_id" => $hotel->id,
                ]);
                // Add Descriptions
                foreach ($reason['descriptions'] as $lang => $description) {
                    $addDescripitonreason = ReasonDescription::create([
                        'description' => $description,
                        'reason_id' => $create_reason['id'],
                        'language_id' => Language::where('key', $lang)->first()->id,
                    ]);
                };

                // Add Descriptions
                foreach ($reason['names'] as $lang => $name) {
                    $addName = ReasonName::create([
                        'name' => $name,
                        'reason_id' => $create_reason['id'],
                        'language_id' => Language::where('key', $lang)->first()->id,
                    ]);
                };

            }

            return $this->jsondata(true, null, "Hotel has Edited successfuly", [], []);
        else:
            return $this->jsondata(false, null, 'Create failed', ["Failed to Create hotel"], []);
        endif;
    }

    public function createRoom(Request $request) {
        $languages = Language::latest()->get();
        $keys = $languages->pluck('key')->all(); // get all Languages key as array

        $currencies = Currency::latest()->get();
        $codes = $currencies->pluck('id')->all(); // get all Languages key as array

        // validate Room Names ---------------------------
        $missingNames = array_diff($keys, array_keys($request->names ? $request->names : [])); // compare keys with names keys to know whitch is missing

        if (!empty($missingNames)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Room Name in (' . Language::where('key', reset($missingNames))->first()->name . ')'], []);
        }
        foreach ($request->names as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Room Name in (' . Language::where('key', $key)->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Room Descriptions ---------------------------
        $missingDescriptions = array_diff($keys, array_keys($request->descriptions ? $request->descriptions : [])); // compare keys with description keys to know whitch is missing

        if (!empty($missingDescriptions)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Room Description in (' . Language::where('key', reset($missingDescriptions))->first()->name . ')'], []);
        }
        foreach ($request->descriptions as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Room Description in (' . Language::where('key', $key)->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Room Prices ---------------------------
        $missingPrices = array_diff($codes, array_keys($request->prices ? $request->prices : [])); // compare keys with description keys to know whitch is missing

        if (!empty($missingPrices)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Room Price in (' . Currency::where('id', reset($missingPrices))->first()->names[0]->name . ')'], []);
        }

        foreach ($request->prices as $key => $value) {
            if (!$value)
            return $this->jsondata(false, null, 'Add failed', ['Please enter Room Price in (' . Currency::where('id', $key)->first()->names[0]->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------


        // then validate each single columns that does not need translation
        $validator = Validator::make($request->all(), [
            'hotel_id' => ['required'],
        ], [
            "hotel_id.required" => "Please enter Hotel Id",
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Create failed', [$validator->errors()->first()], []);
        }

        if (count($request->features ? $request->features : []) < 5) {
            return $this->jsondata(false, null, 'Update failed', ["You have to choose at least 5 features"], []);
        }

        if (count($request->gallery ? $request->gallery : []) < 5) {
            return $this->jsondata(false, null, 'Update failed', ["You have to choose at least 5 images"], []);
        }

        $hotel = Hotel::with("rooms")->find($request->hotel_id);

        $create_room = Room::create([ // If Validation Pass so create the Room
            "hotel_id" => $request->hotel_id,
        ]);

        if ($create_room) :
            // Add Names
            foreach ($request->names as $lang => $name) {
                $addName = RoomName::create([
                    'name' => $name,
                    'room_id' => $create_room->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };
            // Add Descriptions
                foreach ($request->descriptions as $lang => $description) {
                    $addDescripiton = RoomDescription::create([
                        'description' => $description,
                        'room_id' => $create_room->id,
                        'language_id' => Language::where('key', $lang)->first()->id,
                    ]);
                };
            // Add Prices
                foreach ($request->prices as $currency => $prices) {
                    $addPrices = RoomPrice::create([
                        'price' => $prices,
                        'room_id' => $create_room->id,
                        'currency_id' => Currency::where('id', $currency)->first()->id,
                    ]);
                };

            if ($hotel) {
                if ($hotel->rooms->count() == 0) {
                    $hotel->lowest_room_price = $request->prices[0][0];
                    $hotel->save();
                } else {
                    if ((int) $hotel->lowest_room_price > (int) $request->prices[0][0]) {
                        $hotel->lowest_room_price = $request->prices[0][0];
                        $hotel->save();
                    }
                }
            }
            // add Room features
                foreach ($request->features as $feature) {
                    $create_room->features()->attach([$feature['id']]);
                }
            // Add Gallaray images
                foreach ($request->gallery as $img) {
                    $image = $this->saveImg($img, 'images/uploads/Hotels/hotel_' . $request->hotel_id . '/room_' . $create_room->id);
                    if ($image)
                        $upload_image = RoomGallery::create([
                            'path' => '/images/uploads/Hotels/hotel_' . $request->hotel_id . '/room_' . $create_room->id . '/' . $image,
                            'room_id' => $create_room->id,
                        ]);
                }
            return $this->jsondata(true, null, "Room has Added successfuly", [], []);
        else:
            return $this->jsondata(false, null, 'Create failed', ["Failed to Create Room"], []);
        endif;
    }

    public function updateRoom(Request $request) {
        $languages = Language::latest()->get();
        $keys = $languages->pluck('key')->all(); // get all Languages key as array

        $currencies = Currency::latest()->get();
        $codes = $currencies->pluck('id')->all(); // get all Languages key as array

        // validate Room Names ---------------------------
        $missingNames = array_diff($keys, array_keys($request->names ? $request->names : [])); // compare keys with names keys to know whitch is missing

        if (!empty($missingNames)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Room Name in (' . Language::where('key', reset($missingNames))->first()->name . ')'], []);
        }
        foreach ($request->names as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Room Name in (' . Language::where('key', $key)->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Room Descriptions ---------------------------
        $missingDescriptions = array_diff($keys, array_keys($request->descriptions ? $request->descriptions : [])); // compare keys with description keys to know whitch is missing

        if (!empty($missingDescriptions)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Room Description in (' . Language::where('key', reset($missingDescriptions))->first()->name . ')'], []);
        }
        foreach ($request->descriptions as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Room Description in (' . Language::where('key', $key)->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------

        // validate Room Prices ---------------------------
        $missingPrices = array_diff($codes, array_keys($request->prices ? $request->prices : [])); // compare keys with description keys to know whitch is missing

        if (!empty($missingPrices)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Room Price in (' . Currency::where('id', reset($missingPrices))->first()->names[0]->name . ')'], []);
        }

        foreach ($request->prices as $key => $value) {
            if (!$value)
            return $this->jsondata(false, null, 'Add failed', ['Please enter Room Price in (' . Currency::where('id', $key)->first()->names[0]->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------


        // then validate each single columns that does not need translation
        $validator = Validator::make($request->all(), [
            'room_id' => ['required'],
        ], [
            "room_id.required" => "Please enter Room Id",
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Create failed', [$validator->errors()->first()], []);
        }

        if (count($request->features ? $request->features : []) < 5) {
            return $this->jsondata(false, null, 'Update failed', ["You have to choose at least 5 features"], []);
        }

        if (count($request->oldGallery ? $request->oldGallery : []) + count($request->gallery ? $request->gallery : []) < 5) {
            return $this->jsondata(false, null, 'Update failed', ["You have to choose at least 5 Images"], []);
        }

        $create_room = Room::find($request->room_id);

        if ($create_room) :
            // Add Names
            $create_room->names()->delete();
            foreach ($request->names as $lang => $name) {
                $addName = RoomName::create([
                    'name' => $name,
                    'room_id' => $create_room->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };
            // Add Descriptions
            $create_room->descriptions()->delete();
            foreach ($request->descriptions as $lang => $description) {
                $addDescripiton = RoomDescription::create([
                    'description' => $description,
                    'room_id' => $create_room->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };
            // Add Prices
            $create_room->prices()->delete();
                foreach ($request->prices as $currency => $prices) {
                    $addPrices = RoomPrice::create([
                        'price' => $prices,
                        'room_id' => $create_room->id,
                        'currency_id' => Currency::where('id', $currency)->first()->id,
                    ]);
                };

                $create_room->features()->detach();
                // add Room features
                foreach ($request->features as $feature) {
                    $create_room->features()->attach([$feature['id']]);
                }

                $oldGalleryIds = [];
                if ($request->oldGallery)
                foreach ($request->oldGallery as $img) {
                    $oldGalleryIds[] = $img['id'];
                }

                // Fetch Gallery where hotel_id is 3 and id is not in the old gallery IDs
                $removedGallery = Gallery::where('hotel_id', $request->id)
                ->whereNotIn('id', $oldGalleryIds)
                ->get();

                // Remove images from the filesystem for the new gallery
                foreach ($removedGallery as $image) {
                    if (file_exists(public_path($image->path))) {
                        unlink(public_path($image->path));
                    }
                    $image->delete();
                }

            // Add Gallaray images
            if ($request->gallery)
                foreach ($request->gallery as $img) {
                    $image = $this->saveImg($img, 'images/uploads/Hotels/hotel_' . $request->hotel_id . '/room_' . $create_room->id);
                    if ($image)
                        $upload_image = RoomGallery::create([
                            'path' => '/images/uploads/Hotels/hotel_' . $request->hotel_id . '/room_' . $create_room->id . '/' . $image,
                            'room_id' => $create_room->id,
                        ]);
                }
            return $this->jsondata(true, null, "Room has Added successfuly", [], []);
        else:
            return $this->jsondata(false, null, 'Create failed', ["Failed to Create Room"], []);
        endif;
    }

    public function hotel(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Show failed', [$validator->errors()->first()], []);
        }

        $hotel = Hotel::with(["rooms" => function ($q) {
            $q->with("names", "gallery");
        }, "names", "descriptions", "gallery", "addresses", "slogans", "features", "tours" => function($q) {
            $q->with("titles", "intros", "gallery");
        }, "reasons" => function ($q) {
            $q->with("names", "descriptions");
        }])->find($request->id);

        return $hotel;
    }

    public function room(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Show failed', [$validator->errors()->first()], []);
        }

        $room = Room::with([ "names", "descriptions", "gallery", "features", "prices"])->find($request->id);

        return $room;
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'delete failed', [$validator->errors()->first()], []);
        }

        $hotel = Hotel::find($request->id);
        $hotel->names()->delete();
        $hotel->descriptions()->delete();
        $hotel->addresses()->delete();
        $path = 'images/uploads/Hotels/hotel_' . $hotel->id;
        if (\File::exists($path)) \File::deleteDirectory($path);
        $hotel->gallery()->delete();
        $hotel->delete();

        if ($hotel)
            return  $this->jsondata(true, null, 'Hotel has deleted successfuly', [], []);
    }

    public function deleteRoom(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'delete failed', [$validator->errors()->first()], []);
        }

        $room = Room::with("hotel")->find($request->id);
        $room->names()->delete();
        $room->descriptions()->delete();
        $room->prices()->delete();
        $path = 'images/uploads/Hotels/hotel_' . $room->hotel->id. '/room_' . $room->id;
        if (\File::exists($path)) \File::deleteDirectory($path);
        $room->gallery()->delete();
        $room->delete();

        if ($room)
            return  $this->jsondata(true, null, 'Hotel has deleted successfuly', [], []);
    }
}
