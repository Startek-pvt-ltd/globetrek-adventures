<?php
$pageTitle = 'Manage Bookings | GlobeTrek Admin';
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');

$status = $_GET['status'] ?? '';
$date = $_GET['travel_date'] ?? '';
$where = [];
$params = [];
if ($status !== '') {
    $where[] = 'b.booking_status = ?';
    $params[] = $status;
}
if ($date !== '') {
    $where[] = 'b.travel_date = ?';
    $params[] = $date;
}
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
$stmt = $pdo->prepare("SELECT b.*, u.full_name, u.email, p.title FROM bookings b JOIN users u ON u.user_id = b.user_id JOIN packages p ON p.package_id = b.package_id {$whereSql} ORDER BY b.created_at DESC");
$stmt->execute($params);
$bookings = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>
<section class="section alt">
    <div class="container">
        <div class="section-head"><div><p class="eyebrow">Admin</p><h1>Manage Bookings</h1></div><a class="btn btn-outline" href="/globetrek_adventures/dashboard/admin-dashboard.php">Admin Dashboard</a></div>
        <form class="panel grid grid-3" method="get">
            <select name="status">
                <option value="">All statuses</option>
                <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>pending</option>
                <option value="confirmed" <?= $status === 'confirmed' ? 'selected' : '' ?>>confirmed</option>
                <option value="cancelled" <?= $status === 'cancelled' ? 'selected' : '' ?>>cancelled</option>
            </select>
            <input type="date" name="travel_date" value="<?= e($date) ?>">
            <button type="submit">Filter</button>
        </form>
        <div class="panel" style="margin-top: 24px; overflow-x: auto;">
            <table><thead><tr><th>ID</th><th>Traveler</th><th>Package</th><th>Date</th><th>Persons</th><th>Total</th><th>Status</th><th>Payment</th></tr></thead><tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr><td>#<?= e($booking['booking_id']) ?></td><td><?= e($booking['full_name']) ?><br><?= e($booking['email']) ?></td><td><?= e($booking['title']) ?></td><td><?= e($booking['travel_date']) ?></td><td><?= e($booking['num_persons']) ?></td><td><?= e(format_lkr($booking['total_price'])) ?></td><td><?= e($booking['booking_status']) ?></td><td><?= e($booking['payment_status']) ?></td></tr>
                <?php endforeach; ?>
            </tbody></table>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
