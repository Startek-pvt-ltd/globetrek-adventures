<?php
require_once __DIR__ . '/includes/auth.php';
require_login('customer');

$packageId = (int) ($_POST['package_id'] ?? $_GET['package_id'] ?? 0);
$travelDate = $_POST['travel_date'] ?? $_GET['travel_date'] ?? '';
$numPersons = max(1, (int) ($_POST['num_persons'] ?? $_GET['num_persons'] ?? 1));
$stmt = $pdo->prepare("SELECT * FROM packages WHERE package_id = ? AND status = 'active'");
$stmt->execute([$packageId]);
$package = $stmt->fetch();
if (!$package) {
    http_response_code(404);
    exit('Package not found.');
}

// Do not silently clamp submitted values; validate them before saving.
$totalPrice = $numPersons * (float) $package['price'];
$errors = [];
$confirmation = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    // Demo-only flow: never request, receive, or store real card information.
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $travelDate);
    if (!$date || $date->format('Y-m-d') !== $travelDate || $date < new DateTimeImmutable('today')) {
        $errors[] = 'Choose a valid travel date (today or later).';
    }
    if ($numPersons < 1 || $numPersons > (int) $package['max_persons']) {
        $errors[] = 'Number of persons is outside the package limit.';
    }

    if (!$errors) {
        try {
            $pdo->beginTransaction();
            $bookingStmt = $pdo->prepare(
                "INSERT INTO bookings (user_id, package_id, travel_date, num_persons, total_price, payment_status, booking_status) VALUES (?, ?, ?, ?, ?, 'pending', 'pending')"
            );
            $bookingStmt->execute([$_SESSION['user_id'], $packageId, $travelDate, $numPersons, $totalPrice]);
            $bookingId = $pdo->lastInsertId();
            $pdo->commit();
            $confirmation = $bookingId;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Demo booking creation failed: ' . $e->getMessage());
            $errors[] = 'Unable to create the booking. Please try again.';
        }
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
                <p>This is a demonstration booking. No payment was processed or charged. Payment and booking statuses remain pending.</p>
                <a class="btn" href="/globetrek_adventures/dashboard/customer-dashboard.php">View My Bookings</a>
            <?php else: ?>
                <p class="eyebrow">Demonstration only — no payment collected</p>
                <h2>Review Traveler Details</h2>
                <p>This academic demo does not process payments. Do not enter payment card details anywhere in this application.</p>
                <p><?= e($user['full_name']) ?> · <?= e($user['email']) ?> · <?= e($user['phone']) ?></p>
                <form method="post" data-validate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="package_id" value="<?= e($packageId) ?>">
                    <input type="hidden" name="travel_date" value="<?= e($travelDate) ?>">
                    <input type="hidden" name="num_persons" value="<?= e($numPersons) ?>">
                    <?php foreach ($errors as $error): ?><div class="flash error"><?= e($error) ?></div><?php endforeach; ?>
                    <button type="submit">Create Demo Booking (No Payment)</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
