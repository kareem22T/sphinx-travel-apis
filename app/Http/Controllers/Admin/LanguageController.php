<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Traits\DataFormController;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
    use DataFormController;

    public function get() {
        return $currencies = Language::latest()->select("id", "key", "name")->get();
    }

    public function add(Request $request) {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
            'name' => 'required',
        ], [
            'key.required' => 'please enter language key',
            'name.required' => 'please enter language name',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'add failed', [$validator->errors()->first()], []);
        }

        $crete_language = Language::create([
            "key" => $request->key,
            "name" => $request->name
        ]);

        if ($crete_language)
            return  $this->jsondata(true, null, 'Language has added successfuly', [], []);
    }
    
    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'key' => 'required',
            'name' => 'required',
        ], [
            'key.required' => 'please enter language key',
            'name.required' => 'please enter language name',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'update failed', [$validator->errors()->first()], []);
        }

        $currnecy = Language::find($request->id);
        $currnecy->key = $request->key;
        $currnecy->name = $request->name;
        $currnecy->save();

        if ($currnecy)
            return  $this->jsondata(true, null, 'Language has updated successfuly', [], []);
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'delete failed', [$validator->errors()->first()], []);
        }

        $currnecy = Language::find($request->id);
        $currnecy->delete();

        if ($currnecy)
            return  $this->jsondata(true, null, 'Language has deleted successfuly', [], []);
    }
}
