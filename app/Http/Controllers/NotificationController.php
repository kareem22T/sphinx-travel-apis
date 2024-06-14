<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
class NotificationController extends Controller
{

    function sendPushNotification() {
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
            $url = "https://api.push.apple.com:443/3/device/75136D69B4C0E821E3610EE64F00D2824373757F81C6B5BB7FBECE3D02CAA4E1";

            // The payload
            $payload = json_encode([
                'aps' => [
                    'alert' => [
                        'title' => 'Hello',
                        'body' => 'Test Msg',
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

    // $deviceToken = '';
    // $authToken = 'authentication token value';
}
