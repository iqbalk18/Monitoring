<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$res = Illuminate\Support\Facades\Http::get('https://cerebro.ihc.id/api/sap/monitoring/recap', ['limit' => 5, 'page' => 1, 'type' => 'Accrual', 'includeDetails' => 1, 'salesOrganization' => 'BI00', 'status' => 'success,failed', 'fromDate' => '202603', 'toDate' => '202604']);
echo $res->body();
