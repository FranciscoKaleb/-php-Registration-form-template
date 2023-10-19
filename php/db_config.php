<?php
$host = 'localhost';     // The database server's hostname or IP address
$username = 'root';       // Your database username
$password = 'Psalm8psalm90';       // Your database password
$database = 'shopuser'; // The name of your database

// Create a connection to the MySQL database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the character set to UTF-8 (if needed)
mysqli_set_charset($conn, 'utf8');

// You can now use the "$conn" object to perform database operations.

// Don't forget to close the database connection when you're done.
// mysqli_close($conn);
?>
