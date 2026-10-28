<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// ===== إعدادات الإدارة =====
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', '76195012');   // رقم هاتف المطعم
define('PHONE', '96176195012');     // رقم الواتساب بصيغة دولية

// ===== الاتصال بقاعدة البيانات =====
$con = new mysqli('localhost', 'root', '', 'frojwotum');
if ($con->connect_error) {
    die('Cannot connect to the database: ' . $con->connect_error);
}
$con->set_charset('utf8mb4');

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
