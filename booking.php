<?php
require_once __DIR__ . '/includes/auth.php';
require_login('customer');

$packageId = (int) ($_GET['package_id'] ?? $_POST['package_id'] ?? 0);
$travelDate = $_GET['travel_date'] ?? $_POST['travel_date'] ?? '';
$numPersons = max(1, (int) ($_GET['num_persons'] ?? $_POST['num_persons'] ?? 1));
$stmt = $pdo->prepare("SELECT * FROM packages WHERE package_id = ? AND status = 'active'");
$stmt->execute([$packageId]);
$package = $stmt->fetch();
if (!$package) {
    http_response_code(404);
    exit('Package not found.');
}

$numPersons = min($numPersons, (int) $package['max_persons']);
$totalPrice = $numPersons * (float) $package['price'];
$errors = [];
$confirmation = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $cardNumber = preg_replace('/\s+/', '', $_POST['card_number'] ?? '');
    $expiry = trim($_POST['expiry'] ?? '');
    $cvv = trim($_POST['cvv'] ?? '');

    if (!$travelDate || strtotime($travelDate) < strtotime(date('Y-m-d'))) $errors[] = 'Choose a valid future travel date.';
    if ($numPersons < 1 || $numPersons > (int) $package['max_persons']) $errors[] = 'Number of persons is outside the package limit.';
    if (!preg_match('/^\d{12,19}$/', $cardNumber)) $errors[] = 'Enter a valid card number for the simulation.';
    if (!preg_match('/^\d{2}\/\d{2}$/', $expiry)) $errors[] = 'Expiry date must use MM/YY format.';
    if (!preg_match('/^\d{3,4}$/', $cvv)) $errors[] = 'Enter a valid CVV.';

    if (!$errors) {
        $pdo->beginTransaction();
        $bookingStmt = $pdo->prepare("INSERT INTO bookings (user_id, package_id, travel_date, num_persons, total_price, payment_status, booking_status) VALUES (?, ?, ?, ?, ?, 'paid', 'pending')");
        $bookingStmt->execute([$_SESSION['user_id'], $packageId, $travelDate, $numPersons, $totalPrice]);
        $bookingId = $pdo->lastInsertId();
        $paymentStmt = $pdo->prepare('INSERT INTO payments (booking_id, amount, payment_method, transaction_id) VALUES (?, ?, ?, ?)');
        $paymentStmt->execute([$bookingId, $totalPrice, 'Card Simulation', 'GT-' . strtoupper(bin2hex(random_bytes(5)))]);
        $pdo->commit();
        $confirmation = $bookingId;
    }
}

$user = fetch_user($pdo, $_SESSION['user_id']);
$pageTitle = 'Booking | GlobeTrek Adventures';
require_once __DIR__ . '/includes/header.php';
?>
<section class="section alt">
    <div class="container two-col">
        <div class="panel">
            <p class="eyebrow">Booking summary</p>
            <h1><?= e($package['title']) ?></h1>
            <p><strong>Destination:</strong> <?= e($package['destination']) ?></p>
            <p><strong>Travel date:</strong> <?= e($travelDate) ?></p>
            <p><strong>Persons:</strong> <?= e($numPersons) ?></p>
            <p><strong>Total:</strong> <?= e(format_lkr($totalPrice)) ?></p>
        </div>
        <div class="panel">
            <?php if ($confirmation): ?>
                <p class="eyebrow">Confirmed</p>
                <h2>Booking #<?= e($confirmation) ?> created</h2>
                <p>Your payment was simulated successfully. Staff will confirm the travel booking from their dashboard.</p>
                <a class="btn" href="/globetrek_adventures/dashboard/customer-dashboard.php">View My Bookings</a>
            <?php else: ?>
                <p class="eyebrow">Payment simulation</p>
                <h2>Traveler Details</h2>
                <p><?= e($user['full_name']) ?> · <?= e($user['email']) ?> · <?= e($user['phone']) ?></p>
                <form method="post" data-validate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="package_id" value="<?= e($packageId) ?>">
                    <input type="hidden" name="travel_date" value="<?= e($travelDate) ?>">
                    <input type="hidden" name="num_persons" value="<?= e($numPersons) ?>">
                    <?php foreach ($errors as $error): ?><div class="flash error"><?= e($error) ?></div><?php endforeach; ?>
                    <div class="field"><label for="card_number">Card Number</label><input id="card_number" name="card_number" inputmode="numeric" required></div>
                    <div class="two-col">
                        <div class="field"><label for="expiry">Expiry Date</label><input id="expiry" name="expiry" placeholder="MM/YY" required></div>
                        <div class="field"><label for="cvv">CVV</label><input id="cvv" name="cvv" inputmode="numeric" required></div>
                    </div>
                    <button type="submit">Pay & Confirm</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
