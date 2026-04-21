<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$DB_HOST = "127.0.0.1";
$DB_USER = "root";
$DB_PASS = "3473";
$DB_NAME = "ao_003_snippet_storage";

try {
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS);
    $conn->set_charset("utf8mb4");

    $safeDbName = str_replace("`", "``", $DB_NAME);
    $conn->query("
        CREATE DATABASE IF NOT EXISTS `{$safeDbName}`
        CHARACTER SET utf8mb4
        COLLATE utf8mb4_unicode_ci
    ");

    $conn->select_db($DB_NAME);
} catch (Exception $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
