<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $tableName = $argv[1] ?? 'registrations';
    echo "Checking $tableName table structure...\n";

    $columns = \Illuminate\Support\Facades\DB::select("DESCRIBE $tableName");

    foreach ($columns as $column) {
        echo "Field: {$column->Field}, Type: {$column->Type}, Null: {$column->Null}, Key: {$column->Key}, Default: {$column->Default}, Extra: {$column->Extra}\n";
    }

} catch (Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
}
