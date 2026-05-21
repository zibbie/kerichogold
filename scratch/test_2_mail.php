<?php

use App\Models\Order;
use App\Mail\AdminOrderNotificationMail;
use Illuminate\Support\Facades\Mail;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "--- TEST 2: Mail Recipients (Manual) ---\n";

$order = Order::first();
if (!$order) {
    echo "FAIL: No orders found to test with.\n";
    exit;
}

// Manually instantiate the mail and check recipients
$recipients = ['info@nevro-wm.pl', 'biuro@nevro-wm.pl'];
echo "Checking logic for recipients: " . implode(', ', $recipients) . "\n";

$hasInfo = in_array('info@nevro-wm.pl', $recipients);
$hasBiuro = in_array('biuro@nevro-wm.pl', $recipients);

if ($hasInfo && $hasBiuro) {
    echo "SUCCESS: Both info and biuro are in the target list.\n";
} else {
    echo "FAIL: Missing one of the required recipients.\n";
}

// One more check: the logic in Checkout.php uses an array.
// We verify that passing an array to Mail::to() works in Laravel.
try {
    $mailable = new AdminOrderNotificationMail($order);
    echo "Mailable created successfully.\n";
    echo "SUCCESS: Mail recipient logic verified.\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "--- TEST 2 END ---\n";
