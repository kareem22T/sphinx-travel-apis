<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\Notification; 
use App\Models\User; 
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Http;

trait PushNotificationTrait
{
    public function pushNotification($title, $body, $user_id = null, $data = null)
    {
        $CreateNotification = Notification::create([
            "user_id" => $user_id,
            "title" => $title,
            "body" => $body,
        ]);


        $serverKey = 'AAAA-0IfxKc:APA91bEose-nnQ_9aWfGbJkJCx8c-w66gahaB5BgS3TXVKWDph-Wd41myHvV9ME-yjwUAARdH9_xC9b8nLUn6MCaKto3kKyn40cL3jnO1kGrqo3lDrW4uPY7cNSRLCTcNaNOdyQG8mT8';
        $deviceToken = $user_id ? ("/topics/user_" . $user_id) : "/topics/all_users";
        
        $response = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ])
            ->post('https://fcm.googleapis.com/fcm/send', [
                'to' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'data' => $data,
                    'icon' => "https://sphinx-travel.ykdev.online/11Sphinx.png"
                ],
            ]);
        
        // You can then check the response as needed
        if ($response->successful()) {
            // Request was successful
            return $responseData = $response->json();
            // Handle the response data
        } else {
            // Request failed
            return $errorData = $response->json();
            // Handle the error data
        }
    }
}