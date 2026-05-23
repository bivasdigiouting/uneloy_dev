<?php

echo "Testing Login API with user_id and email...\n\n";

// Test 1: Login with user_id
echo "Test 1: Login with user_id 'TEST123'\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/uonly-dev-final/public/api/v1/auth/login');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'user_id' => 'TEST123',
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

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

// Test 2: Login with email
echo "Test 2: Login with email 'test@example.com'\n";
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

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

// Test 3: Invalid credentials
echo "Test 3: Invalid credentials\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/uonly-dev-final/public/api/v1/auth/login');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'user_id' => 'INVALID',
    'password' => 'wrongpassword',
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

echo "Test completed.\n";
