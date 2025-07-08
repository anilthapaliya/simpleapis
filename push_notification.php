<?php

function getAccessToken($serviceAccountFile) {
    $now = time();
    $jwtHeader = base64_encode(json_encode([
        'alg' => 'RS256',
        'typ' => 'JWT'
    ]));

    $serviceAccount = json_decode(file_get_contents($serviceAccountFile), true);

    $iss = $serviceAccount['client_email'];
    $scope = "https://www.googleapis.com/auth/firebase.messaging";
    $aud = $serviceAccount['token_uri'];
    $exp = $now + 3600;
    $iat = $now;

    $jwtClaim = base64_encode(json_encode([
        'iss' => $iss,
        'scope' => $scope,
        'aud' => $aud,
        'exp' => $exp,
        'iat' => $iat
    ]));

    $signatureInput = "$jwtHeader.$jwtClaim";

    // Load private key
    $privateKey = openssl_pkey_get_private($serviceAccount['private_key']);
    openssl_sign($signatureInput, $signature, $privateKey, 'sha256WithRSAEncryption');
    openssl_free_key($privateKey);

    $jwt = "$signatureInput." . base64_encode($signature);

    // Request access token
    $postData = http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $aud);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);
    return $responseData['access_token'] ?? null;
}

function sendFCMNotification($accessToken, $projectId, $deviceToken) {
    $url = "https://fcm.googleapis.com/v1/projects/$projectId/messages:send";

    $notification = [
        'message' => [
            'token' => $deviceToken,
        	//'topic' => 'all',
            'notification' => [
                'title' => 'Push Notification from Server',
                'body' => 'I hope you receive this notification. Cheers!!',
            	'image' => 'https://policies.foliagesoft.com.np/test/images/picture.jpg'
            ],
            // Optional: add data payload here
            'data' => [
                'data_from' => 'Harish',
            	'data_to' => 'Chandra',
            ]
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

// Set paths and values
$serviceAccountPath = __DIR__ . '/bca-8-demo-app.json';
$deviceToken = 'eMJKbSzIQCOuOyNTGiHfLN:APA91bGqXwF4x0A0kg-oBtAsvmoRu1Bj8-xtIee7YP6C1B57xbrAsb1wTFfXAj4n-Fp70gVRE-Mel-JbzNYM1TxOvoc_IxCm2UfmcuPcLlYOJ4wRxbEFc6M';
$projectId = 'bca-8-demo-app';

$token = getAccessToken($serviceAccountPath);

if ($token) {
    $response = sendFCMNotification($token, $projectId, $deviceToken);
    echo "Response:\n$response";
} else {
    echo "Failed to get access token.\n";
}

?>