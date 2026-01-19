<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $details = App\Models\DetailsInvoiceTc::first();
    if (!$details) {
        echo 'No details found.';
        exit;
    }

    $data = $details->attributesToArray();
    unset($data['id'], $data['created_at'], $data['updated_at']);

    echo "Attempting insert for URN: " . ($data['URN'] ?? 'N/A') . "\n";
    App\Models\DoctorsFee::create($data);
    echo 'Success!';
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    // echo $e->getTraceAsString();
}
