<?php

namespace App\Services;

class APNsService
{
    private $keyId;
    private $teamId;
    private $bundleId;
    private $authKey;

    public function __construct()
    {
        $this->keyId = env('APNS_KEY_ID');
        $this->teamId = env('APNS_TEAM_ID');
        $this->bundleId = env('APNS_BUNDLE_ID');
        $this->authKey = file_get_contents(storage_path('app/apns/AuthKey_' . env('APNS_KEY_ID') . '.p8'));
    }

    private function generateJWT()
    {
        $header = [
            'alg' => 'ES256',
            'kid' => $this->keyId,
        ];

        $claims = [
            'iss' => $this->teamId,
            'iat' => time(),
        ];

        $header_encoded = base64_encode(json_encode($header));
        $claims_encoded = base64_encode(json_encode($claims));

        $signature = '';
        openssl_sign($header_encoded . '.' . $claims_encoded, $signature, $this->authKey, 'SHA256');

        return $header_encoded . '.' . $claims_encoded . '.' . base64_encode($signature);
    }

    public function sendNotification($deviceToken, $payload)
    {
        $jwt = $this->generateJWT();

        $url = "https://api.push.apple.com/3/device/{$deviceToken}";

        $headers = [
            'authorization: bearer ' . $jwt,
            'apns-topic: ' . $this->bundleId,
            'apns-push-type: background',
            'apns-priority: 5',
            'apns-expiration: 0',
            'content-type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception("Failed to send notification: {$error}");
        }

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode != 200) {
            throw new \Exception("Failed to send notification: HTTP status code {$statusCode}");
        }

        return $response;
    }
}
