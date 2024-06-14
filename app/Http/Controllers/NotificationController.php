<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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
        $notificationPayload = json_encode([
            'aps' => [
                'alert' => [
                    'title' => 'Hello',
                    'body' => 'Test Msg',
                ],
            ],
        ]);

        // HTTP headers
        $headers = [
            "authorization" => "bearer {$jwt}",
            "apns-topic" => "com.sphinx.travel",
            "apns-push-type" => "alert",
            "apns-priority" => "5",
            "apns-expiration" => "0",
        ];

        // Initialize Guzzle HTTP client
        $client = new Client();

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'body' => $notificationPayload,
                'http_errors' => false, // Disable throwing exceptions on HTTP errors
                // 'version' => 2.0, // Ensure HTTP/2
            ]);

            $httpCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();

            return "HTTP status code: {$httpCode}\nResponse: {$responseBody}";
        } catch (RequestException $e) {
            return "HTTP request error: " . $e->getMessage();
        }
    }
}
