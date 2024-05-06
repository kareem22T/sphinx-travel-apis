<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\DataFormController;
use App\Traits\PushNotificationTrait;
use App\Models\Message;

class MessangerController extends Controller
{
    use DataFormController, PushNotificationTrait;

    public function send(Request $request) {
        $validator = Validator::make($request->all(), [
            'msg' => 'required',
        ], );

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Send failed', [$validator->errors()->first()], []);
        }

        $user = $request->user();

        $message = Message::create(
            [
                "msg" => $request->msg,
                "user_id" => $user->id,
                "is_user_sender" => true,
                "type" => 1,
            ]
            );

        // if first message we recieved your message and we will contact you as soon as posible
        if ($message)
            return true;
    }

    public function userMesages(Request $request) {
        $user = $request->user();
        $messages = [];
        if ($user) {
            $messages = $user->messages()->get();
        }
        return $messages;
    }

}
