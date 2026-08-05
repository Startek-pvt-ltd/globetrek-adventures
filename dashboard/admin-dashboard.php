<?php
$pageTitle = 'Admin Dashboard | GlobeTrek Adventures';
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    if ($action === 'add_staff') {
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($fullName === '' || $email === '' || $phone === '' || $password === '') $errors[] = 'All staff fields are required.';
        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid staff email.';
        if ($password && strlen($password) < 8) $errors[] = 'Staff password must be at least 8 characters.';
        $dupStmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
        $dupStmt->execute([$email]);
        if ($email && $dupStmt->fetchColumn() > 0) $errors[] = 'That email is already used.';
        if (!$errors) {
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, phone, role) VALUES (?, ?, ?, ?, 'staff')");
            $stmt->execute([$fullName, $email, password_hash($password, PASSWORD_DEFAULT), $phone]);
            set_flash('success', 'Staff account added.');
        }
    } elseif ($action === 'delete_staff') {
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ? AND role = 'staff'");
        $stmt->execute([(int) $_POST['user_id']]);
        set_flash('success', 'Staff account deleted.');
    }
    if (!$errors) {
        header('Location: /globetrek_adventures/dashboard/admin-dashboard.php');
        exit;
    }
}

$stats = [
    'bookings' => $pdo->query('SELECT COUNT(*) FROM bookings')->fetchColumn(),
    'revenue' => $pdo->query("SELECT COALESCE(SUM(total_price), 0) FROM bookings WHERE payment_status = 'paid'")->fetchColumn(),
    'customers' => $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn(),
    'packages' => $pdo->query('SELECT COUNT(*) FROM packages')->fetchColumn(),
];
$staff = $pdo->query("SELECT * FROM users WHERE role = 'staff' ORDER BY created_at DESC")->fetchAll();
$users = $pdo->query('SELECT user_id, full_name, email, phone, role, created_at FROM users ORDER BY created_at DESC')->fetchAll();
$bookings = $pdo->query('SELECT b.*, u.full_name, p.title FROM bookings b JOIN users u ON u.user_id = b.user_id JOIN packages p ON p.package_id = b.package_id ORDER BY b.created_at DESC')->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>
<section class="dash">
    <aside class="dash-sidebar">
        <h2>GlobeTrek Admin</h2>
        <a class="active" href="#reports">Reports</a>
        <a href="/globetrek_adventures/admin/manage-staff.php">Manage Staff</a>
        <a href="/globetrek_adventures/admin/manage-packages.php">Manage Packages</a>
        <a href="/globetrek_adventures/admin/manage-bookings.php">All Bookings</a>
        <a href="#users">User Management</a>
        <a href="/globetrek_adventures/logout.php">Logout</a>
    </aside>
    <div class="dash-main">
        <?php foreach ($errors as $error): ?><div class="flash error"><?= e($error) ?></div><?php endforeach; ?>
        <section id="reports">
            <h1>System Dashboard</h1>
            <div class="stat-grid">
                <div class="stat">Total Bookings<strong><?= e($stats['bookings']) ?></strong></div>
                <div class="stat">Total Revenue<strong><?= e(format_lkr($stats['revenue'])) ?></strong></div>
                <div class="stat">Customers<strong><?= e($stats['customers']) ?></strong></div>
                <div class="stat">Packages<strong><?= e($stats['packages']) ?></strong></div>
            </div>
        </section>
        <section id="staff" class="panel">
            <h2>Manage Staff</h2>
            <form class="grid grid-4" method="post" data-validate>
                <?= csrf_field() ?><input type="hidden" name="action" value="add_staff">
                <input name="full_name" placeholder="Full name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input name="phone" placeholder="Phone" required>
                <input type="password" name="password" placeholder="Password" minlength="8" required>
                <button type="submit">Add Staff</button>
            </form>
            <table style="margin-top: 18px;"><thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Action</th></tr></thead><tbody>
                <?php foreach ($staff as $member): ?><tr><td><?= e($member['full_name']) ?></td><td><?= e($member['email']) ?></td><td><?= e($member['phone']) ?></td><td><form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="delete_staff"><input type="hidden" name="user_id" value="<?= e($member['user_id']) ?>"><button class="btn-outline" type="submit">Delete</button></form></td></tr><?php endforeach; ?>
            </tbody></table>
        </section>
        <section id="bookings" class="panel" style="margin-top: 24px;">
            <h2>All Bookings</h2>
            <table><thead><tr><th>ID</th><th>Traveler</th><th>Package</th><th>Date</th><th>Status</th><th>Payment</th></tr></thead><tbody>
                <?php foreach ($bookings as $booking): ?><tr><td>#<?= e($booking['booking_id']) ?></td><td><?= e($booking['full_name']) ?></td><td><?= e($booking['title']) ?></td><td><?= e($booking['travel_date']) ?></td><td><?= e($booking['booking_status']) ?></td><td><?= e($booking['payment_status']) ?></td></tr><?php endforeach; ?>
            </tbody></table>
        </section>
        <section id="users" class="panel" style="margin-top: 24px;">
            <h2>User Management</h2>
            <table><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Role</th></tr></thead><tbody>
                <?php foreach ($users as $user): ?><tr><td><?= e($user['user_id']) ?></td><td><?= e($user['full_name']) ?></td><td><?= e($user['email']) ?></td><td><?= e($user['phone']) ?></td><td><?= e($user['role']) ?></td></tr><?php endforeach; ?>
            </tbody></table>
        </section>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
