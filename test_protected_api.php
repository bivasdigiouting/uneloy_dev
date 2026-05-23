<?php

echo "Testing Protected API endpoints...\n\n";

// First, login to get a token
echo "Step 1: Login to get token\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/uonly-dev-final/public/api/v1/auth/login');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'email' => 'test@example.com',
    'password' => 'password',
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Login HTTP Code: $httpCode\n";
echo "Login Response: $response\n\n";

if ($httpCode === 200) {
    $loginData = json_decode($response, true);
    $token = $loginData['data']['token'];

    echo "Step 2: Test protected profile endpoint\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/uonly-dev-final/public/api/v1/auth/profile');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer '.$token,
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $profileResponse = curl_exec($ch);
    $profileHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "Profile HTTP Code: $profileHttpCode\n";
    echo "Profile Response: $profileResponse\n\n";

    echo "Step 3: Test logout endpoint\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/uonly-dev-final/public/api/v1/auth/logout');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer '.$token,
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $logoutResponse = curl_exec($ch);
    $logoutHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "Logout HTTP Code: $logoutHttpCode\n";
    echo "Logout Response: $logoutResponse\n\n";
} else {
    echo "Login failed, cannot test protected endpoints.\n";
}

echo "Test completed.\n";
