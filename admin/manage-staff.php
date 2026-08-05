<?php
$pageTitle = 'Manage Staff | GlobeTrek Admin';
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $userId = (int) ($_POST['user_id'] ?? 0);

    if ($action === 'save') {
        if ($fullName === '' || $email === '' || $phone === '') $errors[] = 'Name, email, and phone are required.';
        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
        if ($password !== '' && strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';

        $dupStmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ? AND user_id <> ?');
        $dupStmt->execute([$email, $userId]);
        if ($dupStmt->fetchColumn() > 0) $errors[] = 'That email is already used by another account.';

        if (!$errors) {
            if ($userId > 0) {
                if ($password !== '') {
                    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, password = ? WHERE user_id = ? AND role = 'staff'");
                    $stmt->execute([$fullName, $email, $phone, password_hash($password, PASSWORD_DEFAULT), $userId]);
                } else {
                    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ? WHERE user_id = ? AND role = 'staff'");
                    $stmt->execute([$fullName, $email, $phone, $userId]);
                }
                set_flash('success', 'Staff account updated.');
            } else {
                if ($password === '') $errors[] = 'Password is required for new staff accounts.';
                if (!$errors) {
                    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, phone, role) VALUES (?, ?, ?, ?, 'staff')");
                    $stmt->execute([$fullName, $email, password_hash($password, PASSWORD_DEFAULT), $phone]);
                    set_flash('success', 'Staff account added.');
                }
            }
        }
    } elseif ($action === 'delete' && $userId > 0) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ? AND role = 'staff'");
        $stmt->execute([$userId]);
        set_flash('success', 'Staff account deleted.');
    }

    if (!$errors) {
        header('Location: /globetrek_adventures/admin/manage-staff.php');
        exit;
    }
}

$staff = $pdo->query("SELECT * FROM users WHERE role = 'staff' ORDER BY created_at DESC")->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>
<section class="section alt">
    <div class="container">
        <div class="section-head">
            <div><p class="eyebrow">Admin</p><h1>Manage Staff</h1></div>
            <a class="btn btn-outline" href="/globetrek_adventures/dashboard/admin-dashboard.php">Admin Dashboard</a>
        </div>
        <?php foreach ($errors as $error): ?><div class="flash error"><?= e($error) ?></div><?php endforeach; ?>
        <form class="panel grid grid-4" method="post" data-validate>
            <?= csrf_field() ?><input type="hidden" name="action" value="save">
            <input name="full_name" placeholder="Full name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input name="phone" placeholder="Phone" required>
            <input type="password" name="password" placeholder="Password" minlength="8" required>
            <button type="submit">Add Staff</button>
        </form>
        <div class="panel" style="margin-top: 24px; overflow-x: auto;">
            <table>
                <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Password</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($staff as $member): ?>
                        <?php $formId = 'staff-form-' . (int) $member['user_id']; ?>
                        <tr>
                            <td><form id="<?= e($formId) ?>" method="post"><?= csrf_field() ?><input type="hidden" name="action" value="save"><input type="hidden" name="user_id" value="<?= e($member['user_id']) ?>"></form><input form="<?= e($formId) ?>" name="full_name" value="<?= e($member['full_name']) ?>" required></td>
                            <td><input form="<?= e($formId) ?>" type="email" name="email" value="<?= e($member['email']) ?>" required></td>
                            <td><input form="<?= e($formId) ?>" name="phone" value="<?= e($member['phone']) ?>" required></td>
                            <td><input form="<?= e($formId) ?>" type="password" name="password" placeholder="Leave unchanged" minlength="8"></td>
                            <td class="actions"><button form="<?= e($formId) ?>" type="submit">Save</button><form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="user_id" value="<?= e($member['user_id']) ?>"><button class="btn-outline" type="submit">Delete</button></form></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
