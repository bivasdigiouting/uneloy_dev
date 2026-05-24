<?php

/**
 * Quick smoke-test runner for E-Card APIs.
 *
 * Usage:
 *   php ecard_api_test_all.php
 *
 * Notes:
 * - Requires curl.
 * - Update credentials below.
 * - Some endpoints require Bearer token.
 */

$baseUrl = getenv('ECARD_BASE_URL') ?: 'http://localhost/uonly-dev-final/public';

// Update these with valid test data from your DB/dev environment.
$loginPayload = [
    'login' => getenv('ECARD_LOGIN') ?: 'jiarulhossain78@gmail.com',
    'password' => getenv('ECARD_PASSWORD') ?: '12345678',
];


function httpRequest(string $method, string $url, array $headers = [], $body = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    if ($body !== null) {
        if (is_array($body) || is_object($body)) {
            $body = json_encode($body);
            $headers[] = 'Content-Type: application/json';
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    return [
        'http_code' => $httpCode,
        'response' => $response,
        'error' => $err,
    ];
}

function pretty($x)
{
    if (is_string($x)) {
        $decoded = json_decode($x, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
        return $x;
    }
    return print_r($x, true);
}

$results = [];

// 1) Login
$loginUrl = rtrim($baseUrl, '/') . '/api/ecard/login';
$loginRes = httpRequest('POST', $loginUrl, [
    'Accept: application/json',
], $loginPayload);

$results['login'] = $loginRes;

$token = null;
if ($loginRes['http_code'] === 200) {
    $data = json_decode($loginRes['response'], true);
    $token = $data['token'] ?? null;

    if (!$token) {
        // Some clients/implementations may return token under nested keys.
        if (isset($data['data']['token'])) {
            $token = $data['data']['token'];
        }
    }
}


$authHeaders = [
    'Accept: application/json',
];
if ($token) {
    $authHeaders[] = 'Authorization: Bearer ' . $token;
}

// 2) Public master data
$public = [
    ['GET', '/api/ecard/states', null],
    ['GET', '/api/ecard/districts/1', null],
    ['GET', '/api/ecard/cities/1', null],
];
foreach ($public as [$m, $p, $b]) {
    $url = rtrim($baseUrl, '/') . $p;
    $results['public' . $p] = httpRequest($m, $url, $authHeaders, $b);
}

// 3) Auth-required endpoints (only if token exists)
if ($token) {
    $protected = [
        ['POST', '/api/ecard/logout', null],
        ['GET', '/api/ecard/profile', null],
        ['POST', '/api/ecard/profile/update', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'mobile_no' => '9876543210',
        ]],
        ['POST', '/api/ecard/mpin/update', ['mpin' => '1234']],
        ['POST', '/api/ecard/mpin/verify', ['mpin' => '1234']],

        // Wallet
        ['GET', '/api/ecard/wallet/balance', null],
        ['GET', '/api/ecard/wallet/transactions', null],
        ['POST', '/api/ecard/wallet/add-money', ['amount' => 100]],

        // Payment verify (note: route is ANY; use GET as per collection)
        ['GET', '/api/ecard/wallet/verify-payment?order_id=TEST', null],

        // QR
        ['GET', '/api/ecard/qrcode/generate', null],
        ['GET', '/api/ecard/qrcode/view', null],

        // Verification
        ['POST', '/api/ecard/verification/mobile/send', null],
        ['POST', '/api/ecard/verification/mobile/verify', ['otp' => '123456']],
        ['POST', '/api/ecard/verification/email/send', null],
        ['POST', '/api/ecard/verification/email/verify', ['otp' => '123456']],
    ];

    foreach ($protected as [$m, $p, $b]) {
        $url = rtrim($baseUrl, '/') . $p;
        $results['protected' . $p] = httpRequest($m, $url, $authHeaders, $b);
    }
}

file_put_contents(__DIR__ . '/ecard_api_test_results.json', json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo "Done. Results saved to: ecard_api_test_results.json\n";

