<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function format_lkr($amount)
{
    return 'LKR ' . number_format((float) $amount, 2);
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function current_role()
{
    return $_SESSION['role'] ?? 'guest';
}

function require_login($role = null)
{
    if (!is_logged_in()) {
        set_flash('error', 'Please sign in to continue.');
        header('Location: /globetrek_adventures/login.php');
        exit;
    }

    if ($role && current_role() !== $role) {
        http_response_code(403);
        exit('Access denied.');
    }
}

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf()
{
    $token = $_POST['csrf_token'] ?? '';
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(400);
        exit('Invalid form token.');
    }
}

function set_flash($type, $message)
{
    $_SESSION['flash'][$type][] = $message;
}

function get_flash()
{
    $flash = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flash;
}

function redirect_dashboard($role)
{
    $map = [
        'customer' => '/globetrek_adventures/dashboard/customer-dashboard.php',
        'staff' => '/globetrek_adventures/dashboard/staff-dashboard.php',
        'admin' => '/globetrek_adventures/dashboard/admin-dashboard.php',
    ];

    header('Location: ' . ($map[$role] ?? '/globetrek_adventures/index.php'));
    exit;
}

function fetch_user($pdo, $userId)
{
    $stmt = $pdo->prepare('SELECT * FROM users WHERE user_id = ?');
    $stmt->execute([$userId]);
    return $stmt->fetch();
}
