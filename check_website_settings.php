<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

try {
    echo "Checking website_settings table structure...\n";
    $columns = \Illuminate\Support\Facades\DB::select('DESCRIBE website_settings');
    foreach ($columns as $column) {
        echo "Field: {$column->Field}, Type: {$column->Type}, Null: {$column->Null}, Key: {$column->Key}, Default: {$column->Default}, Extra: {$column->Extra}\n";
    }

    echo "\nSHOW CREATE TABLE website_settings:\n";
    $tableInfo = \Illuminate\Support\Facades\DB::select('SHOW CREATE TABLE website_settings');
    echo $tableInfo[0]->{'Create Table'}."\n";
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
}

echo "\nCheck completed\n";
