<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour_rating;
use App\Traits\DataFormController;
use Illuminate\Support\Facades\Validator;

class RateController extends Controller
{
    use DataFormController;

    public function getUnApproved() {
        $ratings = Tour_rating::with(["tour", "user"])->where("approved", false)->all();
        return $ratings;
    }

    public function Approve(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], );

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'approve failed', [$validator->errors()->first()], []);
        }

        $rate = Tour_rating::find($request->id);
        if ($rate) :
            $rate->approve = 1;
            $rate->save();
        endif;

        return $this->jsondata(true, null, 'approve successfuly', [], []);
    }

    public function Reject(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], );

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'approve failed', [$validator->errors()->first()], []);
        }

        $rate = Tour_rating::find($request->id);
        if ($rate) :
            $rate->describe = null;
            $rate->approve = 1;
            $rate->save();
        endif;

        return $this->jsondata(true, null, 'Reject successfuly', [], []);
    }

}
