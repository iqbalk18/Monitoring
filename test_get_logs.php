<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// We need to bypass authentication to get the actual API error
// But Cerebro IHC SAP API is behind auth middleware (token required)
// If I could just read the user's latest error from Laravel logs...
$lines = file(__DIR__.'/storage/logs/laravel.log');
$lastLines = array_slice($lines, -100);
file_put_contents(__DIR__ . '/test_accrual_logs.txt', implode("", $lastLines));
echo "Fetched logs.";
