<?php
session_start();
include('settings.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($username) || empty($password)) {
    $_SESSION['error'] = "Please fill in all fields.";
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch user
$stmt = mysqli_prepare($conn, "SELECT * FROM user WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_record = mysqli_fetch_assoc($result);

if ($user_record) {

    // Get MySQL current time
    $time_result = mysqli_query($conn, "SELECT NOW()");
    $row = mysqli_fetch_row($time_result);
    $mysql_time = $row[0];

    // Check if account is locked
    if (!empty($user_record['locked_until']) && 
        $user_record['locked_until'] > $mysql_time) {
        $remaining = ceil((strtotime($user_record['locked_until']) - strtotime($mysql_time)) / 60);
        $_SESSION['error'] = "Account locked. Try again in $remaining minute(s).";
        header("Location: login.php");
        exit();
    }

    // Verify password
    if (password_verify($password, $user_record['password'])) {
        // Success! Reset attempts
        $reset = mysqli_prepare($conn, "UPDATE user SET login_attempts = 0, locked_until = NULL WHERE username = ?");
        mysqli_stmt_bind_param($reset, "s", $username);
        mysqli_stmt_execute($reset);

        $_SESSION['username'] = $user_record['username'];
        $_SESSION['last_activity'] = time();
        header("Location: manage.php");
        exit();

    } else {
        // Wrong password - increment attempts
        $attempts = $user_record['login_attempts'] + 1;

        if ($attempts >= 3) {
            $lock = mysqli_prepare($conn, "UPDATE user SET login_attempts = ?, locked_until = DATE_ADD(NOW(), INTERVAL 1 MINUTE) WHERE username = ?");
            mysqli_stmt_bind_param($lock, "is", $attempts, $username);
            mysqli_stmt_execute($lock);
            $_SESSION['error'] = "Too many failed attempts. Account locked for 1 minute.";
        } else {
            $remaining = 3 - $attempts;
            $update = mysqli_prepare($conn, "UPDATE user SET login_attempts = ? WHERE username = ?");
            mysqli_stmt_bind_param($update, "is", $attempts, $username);
            mysqli_stmt_execute($update);
            $_SESSION['error'] = "Invalid username or password. $remaining attempt(s) remaining.";
        }

        header("Location: login.php");
        exit();
    }

} else {
    // Username not found
    $_SESSION['error'] = "Invalid username or password.";
    header("Location: login.php");
    exit();
}

mysqli_close($conn);
?>