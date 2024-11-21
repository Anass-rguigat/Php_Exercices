<?php
require_once('config.php');

$db_pwd = $_ENV['db_pwd'];

$dbuser = "root"; 
$dbpass = $db_pwd; 
$host = "localhost"; 
$db = "tp";
$mysqli = new mysqli($host, $dbuser, $dbpass, $db);
date_default_timezone_set("Africa/Casablanca");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Create the users table
$table_users_sql = "
CREATE TABLE IF NOT EXISTS users (
    UserId INT AUTO_INCREMENT PRIMARY KEY,
    UserFirstName VARCHAR(64),
    UserLastName VARCHAR(64),
    UserEmail VARCHAR(128) UNIQUE,
    UserPwd VARCHAR(255),  -- Increase password length to accommodate hashed passwords
    UserAccessLevel VARCHAR(200),
    UserPwdResetCode VARCHAR(200)
);";



// Create the sessions table
$table_sessions_sql = "
CREATE TABLE IF NOT EXISTS sessions (
    SessionId INT AUTO_INCREMENT PRIMARY KEY,
    UserId INT,
    UserIpAddress VARCHAR(45) NOT NULL,
    SessionToken VARCHAR(45) NOT NULL,
    ConnectedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ExpiredAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserId) REFERENCES users(UserId)
);";



// Insert users with password hash
$insert_user_sql = "
INSERT INTO users (UserFirstName, UserLastName, UserEmail, UserPwd, UserAccessLevel)
VALUES 
('John', 'Doe', 'john.doe@example.com', '" . password_hash('password123', PASSWORD_DEFAULT) . "', 'level1'),
('Jane', 'Doe', 'jane.doe@example.com', '" . password_hash('password456', PASSWORD_DEFAULT) . "', 'level2');";



// Get user IDs dynamically
$result = $mysqli->query("SELECT UserId FROM users WHERE UserEmail IN ('john.doe@example.com', 'jane.doe@example.com')");
$user_ids = [];
while ($row = $result->fetch_assoc()) {
    $user_ids[] = $row['UserId'];
}

// Insert sessions for the users
$insert_session_sql = "
INSERT INTO sessions (UserId, UserIpAddress, SessionToken, ConnectedAt, ExpiredAt)
VALUES 
({$user_ids[0]}, '192.168.1.1', 'session_token_1', NOW(), DATE_ADD(NOW(), INTERVAL 1 HOUR)),
({$user_ids[1]}, '192.168.1.2', 'session_token_2', NOW(), DATE_ADD(NOW(), INTERVAL 1 HOUR));";




$mysqli->close();
?>
