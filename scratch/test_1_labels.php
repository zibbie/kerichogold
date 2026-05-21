<?php

use App\Models\Order;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "--- TEST 1: Payment Method Label ---\n";

$testCases = [
    'COD' => 'Za pobraniem',
    'cod' => 'Za pobraniem',
    ' Cod ' => 'Za pobraniem',
    'P24' => 'Przelewy24',
    'BLIK' => 'BLIK',
];

foreach ($testCases as $input => $expected) {
    $order = new Order(['payment_method' => $input]);
    $result = $order->payment_method_label;
    if ($result === $expected) {
        echo "PASS: '$input' -> '$result'\n";
    } else {
        echo "FAIL: '$input' -> '$result' (expected '$expected')\n";
    }
}

echo "--- TEST 1 END ---\n";
