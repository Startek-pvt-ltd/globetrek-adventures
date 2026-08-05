<?php
$pageTitle = 'Customer Dashboard | GlobeTrek Adventures';
require_once __DIR__ . '/../includes/auth.php';
require_login('customer');

$tab = $_GET['tab'] ?? 'bookings';
if (!in_array($tab, ['bookings', 'profile', 'query'], true)) {
    $tab = 'bookings';
}
$errors = [];
$user = fetch_user($pdo, $_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'profile') {
        $fullName = trim($_POST['full_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($fullName === '' || $phone === '') $errors[] = 'Name and phone are required.';
        if ($phone && !preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) $errors[] = 'Enter a valid phone number.';
        if ($password && strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
        if (!$errors) {
            if ($password) {
                $stmt = $pdo->prepare('UPDATE users SET full_name = ?, phone = ?, password = ? WHERE user_id = ?');
                $stmt->execute([$fullName, $phone, password_hash($password, PASSWORD_DEFAULT), $_SESSION['user_id']]);
            } else {
                $stmt = $pdo->prepare('UPDATE users SET full_name = ?, phone = ? WHERE user_id = ?');
                $stmt->execute([$fullName, $phone, $_SESSION['user_id']]);
            }
            $_SESSION['name'] = $fullName;
            set_flash('success', 'Profile updated.');
            header('Location: /globetrek_adventures/dashboard/customer-dashboard.php?tab=profile');
            exit;
        }
        $tab = 'profile';
    }

    if ($action === 'query') {
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        if ($subject === '' || $message === '') $errors[] = 'Subject and message are required.';
        if (!$errors) {
            $stmt = $pdo->prepare('INSERT INTO queries (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$_SESSION['user_id'], $user['full_name'], $user['email'], $subject, $message]);
            set_flash('success', 'Query submitted.');
            header('Location: /globetrek_adventures/dashboard/customer-dashboard.php?tab=query');
            exit;
        }
        $tab = 'query';
    }
}

$bookingStmt = $pdo->prepare('SELECT b.*, p.title FROM bookings b JOIN packages p ON p.package_id = b.package_id WHERE b.user_id = ? ORDER BY b.created_at DESC');
$bookingStmt->execute([$_SESSION['user_id']]);
$bookings = $bookingStmt->fetchAll();

$pageTitle = 'Customer Dashboard | GlobeTrek Adventures';
$activePage = 'dashboard';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="dash">
    <aside class="dash-sidebar">
        <h2>Hello, <?= e($_SESSION['name']) ?></h2>
        <a class="<?= $tab === 'bookings' ? 'active' : '' ?>" href="?tab=bookings">My Bookings</a>
        <a class="<?= $tab === 'profile' ? 'active' : '' ?>" href="?tab=profile">My Profile</a>
        <a class="<?= $tab === 'query' ? 'active' : '' ?>" href="?tab=query">Submit Query</a>
        <a href="/globetrek_adventures/logout.php">Logout</a>
    </aside>
    <div class="dash-main">
        <?php foreach ($errors as $error): ?><div class="flash error"><?= e($error) ?></div><?php endforeach; ?>
        <?php if ($tab === 'bookings'): ?>
            <h1>My Bookings</h1>
            <table><thead><tr><th>ID</th><th>Package</th><th>Travel Date</th><th>Status</th><th>Payment</th><th>Total</th></tr></thead><tbody>
                <?php foreach ($bookings as $booking): ?><tr><td>#<?= e($booking['booking_id']) ?></td><td><?= e($booking['title']) ?></td><td><?= e($booking['travel_date']) ?></td><td><?= e($booking['booking_status']) ?></td><td><?= e($booking['payment_status']) ?></td><td><?= e(format_lkr($booking['total_price'])) ?></td></tr><?php endforeach; ?>
            </tbody></table>
        <?php elseif ($tab === 'profile'): ?>
            <h1>My Profile</h1>
            <form class="panel" method="post" data-validate>
                <?= csrf_field() ?><input type="hidden" name="action" value="profile">
                <div class="field"><label for="full_name">Full Name</label><input id="full_name" name="full_name" value="<?= e($user['full_name']) ?>" required></div>
                <div class="field"><label for="phone">Phone</label><input id="phone" name="phone" value="<?= e($user['phone']) ?>" required></div>
                <div class="field"><label for="password">New Password</label><input id="password" type="password" name="password" minlength="8"></div>
                <button type="submit">Save Profile</button>
            </form>
        <?php else: ?>
            <h1>Submit Query</h1>
            <form class="panel" method="post" data-validate>
                <?= csrf_field() ?><input type="hidden" name="action" value="query">
                <div class="field"><label for="subject">Subject</label><input id="subject" name="subject" required></div>
                <div class="field"><label for="message">Message</label><textarea id="message" name="message" required></textarea></div>
                <button type="submit">Send Query</button>
            </form>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
