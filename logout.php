<?php
require_once __DIR__ . '/includes/auth.php';
session_unset();
session_destroy();
session_start();
set_flash('success', 'You have been logged out.');
header('Location: /globetrek_adventures/login.php');
exit;
