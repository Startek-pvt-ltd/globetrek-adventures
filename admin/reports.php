<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');
header('Location: /globetrek_adventures/dashboard/admin-dashboard.php#reports');
exit;
