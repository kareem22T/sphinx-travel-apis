<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Currency;
use App\Traits\DataFormController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    use DataFormController;

    public function get() {
        return $currencies = Currency::latest()->select("id", "code", "name")->get();
    }

    public function add(Request $request) {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required',
        ], [
            'code.required' => 'please enter currency code',
            'name.required' => 'please enter currency name',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'add failed', [$validator->errors()->first()], []);
        }

        $crete_currency = Currency::create([
            "code" => $request->code,
            "name" => $request->name
        ]);

        if ($crete_currency)
            return  $this->jsondata(true, null, 'Currency has added successfuly', [], []);
    }
    
    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'code' => 'required',
            'name' => 'required',
        ], [
            'code.required' => 'please enter currency code',
            'name.required' => 'please enter currency name',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'update failed', [$validator->errors()->first()], []);
        }

        $currnecy = Currency::find($request->id);
        $currnecy->code = $request->code;
        $currnecy->name = $request->name;
        $currnecy->save();

        if ($currnecy)
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
