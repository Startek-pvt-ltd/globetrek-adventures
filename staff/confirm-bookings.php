<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('staff');
header('Location: /globetrek_adventures/dashboard/staff-dashboard.php#bookings');
exit;
