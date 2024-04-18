<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Currency;

class CurrencyController extends Controller
{
    public function getCurrencies(Request $request) {
        $lang = Language::where("key", $request->lang ? $request->lang : "EN")->first();

        $currencies = Currency::with(["names" => function ($q) use ($lang) {
            if ($lang)
                $q->where("language_id", $lang->id);
        }])->get();

        return $currencies;
    }
}
