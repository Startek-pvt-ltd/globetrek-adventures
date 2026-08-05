<?php
require_once __DIR__ . '/includes/auth.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $phone = trim($_POST['phone'] ?? '');

    if ($fullName === '' || $email === '' || $password === '' || $confirmPassword === '' || $phone === '') $errors[] = 'All fields are required.';
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
    if ($password !== $confirmPassword) $errors[] = 'Passwords must match.';
    if ($phone && !preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) $errors[] = 'Enter a valid phone number.';

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($email && $stmt->fetchColumn() > 0) $errors[] = 'An account with this email already exists.';

    if (!$errors) {
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, phone, role) VALUES (?, ?, ?, ?, 'customer')");
        $stmt->execute([$fullName, $email, password_hash($password, PASSWORD_DEFAULT), $phone]);
        set_flash('success', 'Registration successful. Please sign in.');
        header('Location: /globetrek_adventures/login.php');
        exit;
    }
}

$pageTitle = 'Register | GlobeTrek Adventures';
$activePage = 'register';
require_once __DIR__ . '/includes/header.php';
?>
<section class="form-shell">
    <p class="eyebrow">Create account</p>
    <h1>Register</h1>
    <form method="post" data-validate>
        <?= csrf_field() ?>
        <?php foreach ($errors as $error): ?><div class="flash error"><?= e($error) ?></div><?php endforeach; ?>
        <div class="field"><label for="full_name">Full Name</label><input id="full_name" name="full_name" required value="<?= e($_POST['full_name'] ?? '') ?>"></div>
        <div class="field"><label for="email">Email</label><input id="email" type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"></div>
        <div class="field"><label for="phone">Phone Number</label><input id="phone" name="phone" required value="<?= e($_POST['phone'] ?? '') ?>"></div>
        <div class="field"><label for="password">Password</label><input id="password" type="password" name="password" minlength="8" required></div>
        <div class="field"><label for="confirm_password">Confirm Password</label><input id="confirm_password" type="password" name="confirm_password" minlength="8" required></div>
        <button type="submit">Register</button>
    </form>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
