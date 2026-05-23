<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first();
if ($user) {
    echo 'User ID: '.$user->id."\n";
    echo 'State: '.$user->state.' (Type: '.gettype($user->state).")\n";
    echo 'District: '.$user->district."\n";
    echo 'City: '.$user->city."\n";
} else {
    echo "No user found.\n";
}
