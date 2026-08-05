<?php
require_once __DIR__ . '/includes/auth.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Email and password are required.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['full_name'];
            redirect_dashboard($user['role']);
        }
        $error = 'Wrong email or password.';
    }
}

$pageTitle = 'Login | GlobeTrek Adventures';
$activePage = 'login';
require_once __DIR__ . '/includes/header.php';
?>
<section class="form-shell">
    <p class="eyebrow">Welcome back</p>
    <h1>Login</h1>
    <form method="post" data-validate>
        <?= csrf_field() ?>
        <?php if ($error): ?><div class="flash error"><?= e($error) ?></div><?php endif; ?>
        <div class="field"><label for="email">Email</label><input id="email" type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"></div>
        <div class="field"><label for="password">Password</label><input id="password" type="password" name="password" required></div>
        <button type="submit">Sign In</button>
    </form>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
