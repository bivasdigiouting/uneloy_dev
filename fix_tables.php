<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Fixing database tables...\n";

    // Get the current max ID from migrations table
    $maxMigrationId = \Illuminate\Support\Facades\DB::table('migrations')->max('id') ?: 0;
    echo "Max migration ID: $maxMigrationId\n";

    // Fix migrations table with proper auto_increment value
    \Illuminate\Support\Facades\DB::statement('ALTER TABLE migrations MODIFY id int(10) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT='.($maxMigrationId + 1));
    echo "Fixed migrations table\n";

    // Get the current max ID from personal_access_tokens table
    $maxTokenId = \Illuminate\Support\Facades\DB::table('personal_access_tokens')->max('id') ?: 0;
    echo "Max token ID: $maxTokenId\n";

    // Fix personal_access_tokens table with proper auto_increment value
    \Illuminate\Support\Facades\DB::statement('ALTER TABLE personal_access_tokens MODIFY id bigint(20) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT='.($maxTokenId + 1));
    echo "Fixed personal_access_tokens table\n";

    echo "\nTesting token creation...\n";
    $user = \App\Models\User::where('email', 'test@example.com')->first();
    if ($user) {
        $token = $user->createToken('test_token');
        echo 'Token created successfully: '.substr($token->plainTextToken, 0, 20)."...\n";
    }

} catch (Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
}

echo "\nFix completed\n";
