<?php
// Database configuration parameters for XAMPP
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', ''); // Default XAMPP password is empty
define('DB_NAME', 'shoes_store');

// Establish MySQL connection
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verify database connection state
if (!$conn) {
    die("Database Connection Error: " . mysqli_connect_error());
}
?>