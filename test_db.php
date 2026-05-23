<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing database connection...\n";
    $userCount = \App\Models\User::count();
    echo "Users in database: $userCount\n";

    echo "\nTesting direct token creation...\n";
    $user = \App\Models\User::where('email', 'test@example.com')->first();
    if ($user) {
        echo "Found user: {$user->email} (ID: {$user->id})\n";

        // Try to create token manually
        $tokenModel = new \Laravel\Sanctum\PersonalAccessToken;
        $tokenModel->tokenable_type = get_class($user);
        $tokenModel->tokenable_id = $user->id;
        $tokenModel->name = 'test_token';
        $tokenModel->token = hash('sha256', $plainTextToken = \Illuminate\Support\Str::random(40));
        $tokenModel->abilities = ['*'];

        echo "Attempting to save token...\n";
        $tokenModel->save();
        echo "Token saved successfully!\n";

    } else {
        echo "User not found\n";
    }

} catch (Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
    echo 'File: '.$e->getFile().' Line: '.$e->getLine()."\n";

    // Check if it's a database error
    if ($e->getPrevious()) {
        echo 'Previous error: '.$e->getPrevious()->getMessage()."\n";
    }
}

echo "\nTest completed\n";
