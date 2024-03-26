<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Traits\DataFormController;
use App\Traits\PushNotificationTrait;
use App\Models\Message;
use App\Models\User;

class MessangerController extends Controller
{
    use DataFormController, PushNotificationTrait;
    public function getChats() {
        $chats = User::whereHas("messages")->take(100)->get();
        return $chats;
    }

    public function send(Request $request) {
        $validator = Validator::make($request->all(), [
            'msg' => 'required',
            'user_id' => 'required',
        ], );

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Send failed', [$validator->errors()->first()], []);
        }

        $user = $request->user();

        $message = Message::create(
            [
                "msg" => $request->msg,
                "user_id" => $request->user_id,
                "is_user_sender" => false,
                "type" => 1,
            ]
            );

        $serverKey = 'AAAA-0IfxKc:APA91bEose-nnQ_9aWfGbJkJCx8c-w66gahaB5BgS3TXVKWDph-Wd41myHvV9ME-yjwUAARdH9_xC9b8nLUn6MCaKto3kKyn40cL3jnO1kGrqo3lDrW4uPY7cNSRLCTcNaNOdyQG8mT8';
        $deviceToken = "/topics/MsgUser_" . $request->user_id;
        
        $response = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ])
            ->post('https://fcm.googleapis.com/fcm/send', [
                'to' => $deviceToken,
                'notification' => [
                    'title' => "New Message",
                    'body' => $request->msg,
                    'icon' => "https://sphinx-travel.ykdev.online/11Sphinx.png"
                ],
            ]);    

        if ($message)
            return true;
    }

    public function getChat(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ], );

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Send failed', [$validator->errors()->first()], []);
        }

        $user = User::find($request->user_id);

        $messages = [];
        if ($user) {
            $messages = $user->messages()->get();
        }
        return $messages;
    }
}
