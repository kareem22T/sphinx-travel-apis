<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\User;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;

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

    public function pushNotificationIos($token, $title, $msg) {
            $teamId = '7A55RYWJKX'; // Replace with your Team ID
            $keyId = '82Z9QA7FVZ'; // Replace with your Key ID
            $privateKey = file_get_contents(storage_path('app/apns/AuthKey_82Z9QA7FVZ.p8')); // Replace with the path to your .p8 file

            // Token expiration time (typically 1 hour)
            $time = time();
            $expirationTime = $time + 3600;

            // Payload
            $payload = [
                'iss' => $teamId,
                'iat' => $time,
                'exp' => $expirationTime,
            ];

            // Generate the JWT
            $jwt = JWT::encode($payload, $privateKey, 'ES256', $keyId);
            // APNs URL
            $url = "https://api.push.apple.com:443/3/device/" . $token;

            // The payload
            $payload = json_encode([
                'aps' => [
                    'alert' => [
                        'title' => $title,
                        'body' => $msg,
                    ],
                ],
            ]);

            // HTTP headers
            $headers = [
                "authorization: bearer {$jwt}",
                "apns-topic: com.sphinx.travel",
                "apns-push-type: alert",
                "apns-priority: 5",
                "apns-expiration: 0",
            ];

            // Initialize cURL
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_PORT, 443);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

            // Execute cURL
            $response = curl_exec($ch);

            // Check for errors
            if ($response === false) {
                $error = curl_error($ch);
                curl_close($ch);
                return "cURL error: {$error}";
            }

            // Get HTTP status code
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Close cURL
            curl_close($ch);

            // Return response
            return "HTTP status code: {$httpCode}\nResponse: {$response}";
    }

}
