<!-- -- setup_database.sql -->

<?php
require 'config.php';
// Create database
$db_name = 'foodsavedb';


$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully or already exists<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn);
}


// Select the database to creae the tables
$conn->select_db($db_name);

// SQL statements for creating tables
$sql = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    food_type VARCHAR(255) NOT NULL,
    quantity VARCHAR(255) NOT NULL,
    pickup_time VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
";

if (mysqli_multi_query($conn, $sql)) {
    echo "Tables created successfully";
} else {
    echo "Error creating tables: " . mysqli_error($conn);
}

$conn->close();
?>

