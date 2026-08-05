<?php
$pageTitle = 'Staff Dashboard | GlobeTrek Adventures';
require_once __DIR__ . '/../includes/auth.php';
require_login('staff');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    if ($action === 'booking') {
        $status = $_POST['status'] ?? '';
        if (!in_array($status, ['confirmed', 'cancelled'], true)) {
            set_flash('error', 'Invalid booking status.');
            header('Location: /globetrek_adventures/dashboard/staff-dashboard.php#bookings');
            exit;
        }
        $stmt = $pdo->prepare('UPDATE bookings SET booking_status = ? WHERE booking_id = ?');
        $stmt->execute([$status, (int) $_POST['booking_id']]);
        set_flash('success', 'Booking updated.');
    } elseif ($action === 'query') {
        $response = trim($_POST['response'] ?? '');
        if ($response === '') {
            set_flash('error', 'Response cannot be empty.');
            header('Location: /globetrek_adventures/dashboard/staff-dashboard.php#queries');
            exit;
        }
        $stmt = $pdo->prepare("UPDATE queries SET response = ?, status = 'responded' WHERE query_id = ?");
        $stmt->execute([$response, (int) $_POST['query_id']]);
        set_flash('success', 'Query response saved.');
    } elseif ($action === 'package') {
        if (!in_array($_POST['status'] ?? '', ['active', 'inactive'], true)) {
            set_flash('error', 'Invalid package status.');
            header('Location: /globetrek_adventures/dashboard/staff-dashboard.php#packages');
            exit;
        }
        $stmt = $pdo->prepare('UPDATE packages SET title = ?, destination = ?, activities = ?, price = ?, duration_days = ?, image_path = ?, status = ? WHERE package_id = ?');
        $stmt->execute([trim($_POST['title']), trim($_POST['destination']), trim($_POST['activities'] ?? ''), (float) $_POST['price'], (int) $_POST['duration_days'], trim($_POST['image_path'] ?? ''), $_POST['status'], (int) $_POST['package_id']]);
        set_flash('success', 'Package updated.');
    }
    header('Location: /globetrek_adventures/dashboard/staff-dashboard.php');
    exit;
}

$packages = $pdo->query('SELECT * FROM packages ORDER BY created_at DESC')->fetchAll();
$bookings = $pdo->query('SELECT b.*, u.full_name, p.title FROM bookings b JOIN users u ON u.user_id = b.user_id JOIN packages p ON p.package_id = b.package_id ORDER BY b.created_at DESC')->fetchAll();
$queries = $pdo->query('SELECT * FROM queries ORDER BY created_at DESC')->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>
<section class="dash">
    <aside class="dash-sidebar">
        <h2>Staff Console</h2>
        <a class="active" href="#packages">Manage Packages</a>
        <a href="#bookings">View Bookings</a>
        <a href="#queries">Customer Queries</a>
        <a href="/globetrek_adventures/logout.php">Logout</a>
    </aside>
    <div class="dash-main">
        <h1>Staff Dashboard</h1>
        <section id="packages" class="panel">
            <h2>Manage Packages</h2>
            <table><thead><tr><th>Package</th><th>Destination</th><th>Price</th><th>Duration</th><th>Status</th><th>Action</th></tr></thead><tbody>
                <?php foreach ($packages as $package): ?><?php $formId = 'staff-package-form-' . (int) $package['package_id']; ?><tr><td><form id="<?= e($formId) ?>" method="post"><?= csrf_field() ?><input type="hidden" name="action" value="package"><input type="hidden" name="package_id" value="<?= e($package['package_id']) ?>"></form><input form="<?= e($formId) ?>" name="title" value="<?= e($package['title']) ?>"></td><td><input form="<?= e($formId) ?>" name="destination" value="<?= e($package['destination']) ?>"><input form="<?= e($formId) ?>" name="activities" value="<?= e($package['activities'] ?? '') ?>"></td><td><input form="<?= e($formId) ?>" type="number" step="0.01" name="price" value="<?= e($package['price']) ?>"></td><td><input form="<?= e($formId) ?>" type="number" name="duration_days" value="<?= e($package['duration_days']) ?>"><input form="<?= e($formId) ?>" name="image_path" value="<?= e($package['image_path'] ?? '') ?>"></td><td><select form="<?= e($formId) ?>" name="status"><option value="active" <?= $package['status'] === 'active' ? 'selected' : '' ?>>active</option><option value="inactive" <?= $package['status'] === 'inactive' ? 'selected' : '' ?>>inactive</option></select></td><td><button form="<?= e($formId) ?>" type="submit">Save</button></td></tr><?php endforeach; ?>
            </tbody></table>
        </section>
        <section id="bookings" class="panel" style="margin-top: 24px;">
            <h2>View Bookings</h2>
            <table><thead><tr><th>ID</th><th>Traveler</th><th>Package</th><th>Date</th><th>Status</th><th>Action</th></tr></thead><tbody>
                <?php foreach ($bookings as $booking): ?><tr><td>#<?= e($booking['booking_id']) ?></td><td><?= e($booking['full_name']) ?></td><td><?= e($booking['title']) ?></td><td><?= e($booking['travel_date']) ?></td><td><?= e($booking['booking_status']) ?></td><td><form class="actions" method="post"><?= csrf_field() ?><input type="hidden" name="action" value="booking"><input type="hidden" name="booking_id" value="<?= e($booking['booking_id']) ?>"><button name="status" value="confirmed">Confirm</button><button class="btn-outline" name="status" value="cancelled">Cancel</button></form></td></tr><?php endforeach; ?>
            </tbody></table>
        </section>
        <section id="queries" class="panel" style="margin-top: 24px;">
            <h2>Customer Queries</h2>
            <table><thead><tr><th>From</th><th>Subject</th><th>Message</th><th>Response</th></tr></thead><tbody>
                <?php foreach ($queries as $query): ?><tr><td><?= e($query['name']) ?><br><?= e($query['email']) ?></td><td><?= e($query['subject']) ?><br><span class="pill"><?= e($query['status']) ?></span></td><td><?= e($query['message']) ?></td><td><form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="query"><input type="hidden" name="query_id" value="<?= e($query['query_id']) ?>"><textarea name="response" required><?= e($query['response']) ?></textarea><button type="submit">Respond</button></form></td></tr><?php endforeach; ?>
            </tbody></table>
        </section>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
