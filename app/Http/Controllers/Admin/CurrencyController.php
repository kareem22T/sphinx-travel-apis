<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\Language;
use App\Models\CurrencyName;
use App\Traits\DataFormController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    use DataFormController;

    public function get() {
        return $currencies = Currency::latest()->with("names")->get();
    }

    public function add(Request $request) {
        $languages = Language::latest()->get();
        $keys = $languages->pluck('key')->all(); // get all Languages key as array

        // validate Hotel Names ---------------------------
        $missingNames = array_diff($keys, array_keys($request->names ? $request->names : [])); // compare keys with names keys to know whitch is missing

        if (!empty($missingNames)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Currency Name in (' . Language::where('key', reset($missingNames))->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------

        $validator = Validator::make($request->all(), [
            'code' => 'required',
            // 'name' => 'required',
        ], [
            'code.required' => 'please enter currency code',
            // 'name.required' => 'please enter currency name',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'add failed', [$validator->errors()->first()], []);
        }

        $crete_currency = Currency::create([
            "code" => $request->code,
            // "name" => $request->name
        ]);

        if ($crete_currency) :
            // Add Names
            foreach ($request->names as $lang => $name) {
                $addName = CurrencyName::create([
                    'name' => $name,
                    'currency_id' => $crete_currency->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    
        endif;

        if ($crete_currency)
            return  $this->jsondata(true, null, 'Currency has added successfuly', [], []);
    }
    
    public function update(Request $request) {
        $languages = Language::latest()->get();
        $keys = $languages->pluck('key')->all(); // get all Languages key as array

        // validate Hotel Names ---------------------------
        $missingNames = array_diff($keys, array_keys($request->names ? $request->names : [])); // compare keys with names keys to know whitch is missing

        if (!empty($missingNames)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter Currency Name in (' . Language::where('key', reset($missingNames))->first()->name . ')'], []);
        }
        foreach ($request->names as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter Currency Name in (' . Language::where('key', $key)->first()->name . ')'], []);
        }    
        // ----------------------------------------------------------------------------------------------------------------------

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'code' => 'required',
            // 'name' => 'required',
        ], [
            'code.required' => 'please enter currency code',
            'name.required' => 'please enter currency name',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'update failed', [$validator->errors()->first()], []);
        }

        $currency = Currency::find($request->id);
        $currency->code = $request->code;
        $currency->names()->delete();

        if ($currency) :
            // Add Names
            foreach ($request->names as $lang => $name) {
                $addName = CurrencyName::create([
                    'name' => $name,
                    'currency_id' => $currency->id,
                    'language_id' => Language::where('key', $lang)->first()->id,
                ]);
            };    
        endif;
        $currency->save();

        if ($currency)
            return  $this->jsondata(true, null, 'Currency has updated successfuly', [], []);
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'delete failed', [$validator->errors()->first()], []);
        }

        $currnecy = Currency::find($request->id);
        $currnecy->delete();

        if ($currnecy)
            return  $this->jsondata(true, null, 'Currency has deleted successfuly', [], []);
    }
}
