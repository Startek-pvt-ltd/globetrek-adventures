<?php
require_once __DIR__ . '/auth.php';
$pageTitle = $pageTitle ?? 'GlobeTrek Adventures';
$activePage = $activePage ?? '';
$flashMessages = get_flash();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/globetrek_adventures/css/style.css">
</head>
<body>
<header class="site-header">
    <nav class="navbar container">
        <a class="brand" href="/globetrek_adventures/index.php">GlobeTrek Adventures</a>
        <button class="nav-toggle" type="button" aria-label="Toggle navigation">Menu</button>
        <div class="nav-links">
            <a class="<?= $activePage === 'home' ? 'active' : '' ?>" href="/globetrek_adventures/index.php">Home</a>
            <a class="<?= $activePage === 'packages' ? 'active' : '' ?>" href="/globetrek_adventures/packages.php">Packages</a>
            <a class="<?= $activePage === 'destinations' ? 'active' : '' ?>" href="/globetrek_adventures/destinations.php">Destinations</a>
            <a class="<?= $activePage === 'accommodation' ? 'active' : '' ?>" href="/globetrek_adventures/accommodation.php">Stay</a>
            <a class="<?= $activePage === 'transportation' ? 'active' : '' ?>" href="/globetrek_adventures/transportation.php">Transport</a>
            <a class="<?= $activePage === 'guides' ? 'active' : '' ?>" href="/globetrek_adventures/travel-guides.php">Guides</a>
            <a class="<?= $activePage === 'about' ? 'active' : '' ?>" href="/globetrek_adventures/about.php">About</a>
            <a class="<?= $activePage === 'contact' ? 'active' : '' ?>" href="/globetrek_adventures/contact.php">Contact</a>
            <?php if (is_logged_in()): ?>
                <a href="/globetrek_adventures/dashboard/<?= e(current_role()) ?>-dashboard.php">Dashboard</a>
                <a class="btn btn-small" href="/globetrek_adventures/logout.php">Logout</a>
            <?php else: ?>
                <a class="<?= $activePage === 'login' ? 'active' : '' ?>" href="/globetrek_adventures/login.php">Login</a>
                <a class="btn btn-small" href="/globetrek_adventures/register.php">Register</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
<main>
    <?php if ($flashMessages): ?>
        <section class="container flash-wrap">
            <?php foreach ($flashMessages as $type => $messages): ?>
                <?php foreach ($messages as $message): ?>
                    <div class="flash <?= e($type) ?>"><?= e($message) ?></div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
