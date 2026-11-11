<?php
// Local PHP/MySQL demonstration configuration. Set environment values on real hosts.
// Development defaults target a local XAMPP instance only; never use root/blank credentials publicly.
mysqli_report(MYSQLI_REPORT_OFF);
$host = getenv("DB_HOST") ?: "127.0.0.1";
$username = getenv("DB_USER") ?: "root";
$password = getenv("DB_PASSWORD");
$password = $password === false ? "" : $password;
$database = getenv("DB_NAME") ?: "shoes_store";
$conn = @mysqli_connect($host, $username, $password, $database);
if (!$conn) {
    http_response_code(503);
    error_log("Sumitra store DB connection unavailable");
    exit("Catalog temporarily unavailable. Check local database configuration.");
}
mysqli_set_charset($conn, "utf8mb4");
