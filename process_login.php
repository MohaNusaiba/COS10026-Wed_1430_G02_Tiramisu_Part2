<?php
/*
 * process_login.php - Login Processing Logic
 * Handles all authentication logic for the HR manager portal.
 * Never displays anything directly - only processes, validates and redirects.
 * Separated from login.php following the separation of concerns principle:
 * login.php shows the form, process_login.php handles the logic.
 */

session_start(); // Initialise session so we can read POST data and write session variables
include('settings.php'); // Load $host, $user, $pwd, $sql_db for DB connection

// Block direct URL access - this file should only be reached by submitting login.php
// $_SERVER['REQUEST_METHOD'] tells us how the page was accessed
// Typing the URL directly is a GET request not POST - redirect away immediately
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

// Retrieve and sanitise form inputs
// trim() removes accidental whitespace so " admin " matches "admin"
// ?? '' provides a safe empty string default if the POST key doesn't exist
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

// Server-side empty check - even though login.php has required attributes
// HTML validation can be bypassed by sending a POST request directly
// so we always validate server-side as a safety net
if (empty($username) || empty($password)) {
    // Store error in session so it survives the redirect back to login.php
    // We can't echo directly here since we're about to redirect
    $_SESSION['error'] = "Please fill in all fields.";
    header("Location: login.php");
    exit();
}

// Connect to DB using credentials from settings.php
$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch user by username only - NOT by password
// We never pass the password into the SQL query
// Instead we use password_verify() after fetching to compare safely
// Prepared statement with ? placeholder prevents SQL injection -
// even if someone types malicious SQL as their username it is treated as plain text
$stmt = mysqli_prepare($conn, "SELECT * FROM user WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username); // s = string type
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// fetch_assoc returns the matching row as an associative array
// Returns null if no user with that username exists
$user_record = mysqli_fetch_assoc($result);

if ($user_record) {
    // Username was found in the DB - now check lockout and password

    // Get current time directly from MySQL rather than PHP's time()
    // We discovered XAMPP runs PHP and MySQL in different timezones
    // causing an 8 hour difference that made lockouts never expire
    // By getting time from MySQL both sides of the comparison are in the same timezone
    $time_result = mysqli_query($conn, "SELECT NOW()");
    $row         = mysqli_fetch_row($time_result);
    $mysql_time  = $row[0]; // [0] because fetch_row returns indexed array, no column name

    // Check if the account is currently locked
    // !empty() handles both NULL and empty string from the DB
    // Compare locked_until directly against MySQL time as datetime strings
    if (!empty($user_record['locked_until']) &&
        $user_record['locked_until'] > $mysql_time) {

        // Calculate remaining lock time in minutes
        // strtotime() converts datetime strings to Unix timestamps for maths
        // ceil() rounds UP so 61 seconds shows as 2 minutes not 1
        $remaining = ceil(
            (strtotime($user_record['locked_until']) - strtotime($mysql_time)) / 60
        );
        $_SESSION['error'] = "Account locked. Try again in $remaining minute(s).";
        header("Location: login.php");
        exit();
    }

    // Verify the entered password against the bcrypt hash stored in DB
    // We cannot compare hashes directly - bcrypt adds a random salt each time
    // so the same password produces a different hash on every hash() call
    // password_verify() extracts the salt from the stored hash and uses it
    // to hash the input the same way then compares - the only correct approach
    if (password_verify($password, $user_record['password'])) {

        // Successful login - reset attempt counter and clear any lockout
        // This gives a clean slate so a legitimate user who previously
        // failed a few times can try again without being penalised
        $reset = mysqli_prepare($conn,
            "UPDATE user SET login_attempts = 0, locked_until = NULL WHERE username = ?"
        );
        mysqli_stmt_bind_param($reset, "s", $username);
        mysqli_stmt_execute($reset);

        // Store username in session so manage.php knows who is logged in
        // Store last_activity timestamp for session timeout tracking in manage.php
        $_SESSION['username']      = $user_record['username'];
        $_SESSION['last_activity'] = time();

        // Redirect to the protected manager portal
        header("Location: manage.php");
        exit();

    } else {
        // Wrong password - increment the attempt counter in the DB
        // DB-side tracking is more secure than session-based tracking because
        // it persists even if the user closes the browser or tries incognito
        $attempts = $user_record['login_attempts'] + 1;

        if ($attempts >= 3) {
            // Lock the account for 1 minute using MySQL DATE_ADD
            // Calculated entirely in MySQL so timezone issues cannot affect it
            // 'is' in bind_param: i = integer (attempts), s = string (username)
            $lock = mysqli_prepare($conn,
                "UPDATE user SET login_attempts = ?,
                 locked_until = DATE_ADD(NOW(), INTERVAL 1 MINUTE)
                 WHERE username = ?"
            );
            mysqli_stmt_bind_param($lock, "is", $attempts, $username);
            mysqli_stmt_execute($lock);
            $_SESSION['error'] = "Too many failed attempts. Account locked for 1 minute.";

        } else {
            // Under 3 attempts - just increment the counter and warn the user
            $remaining = 3 - $attempts;
            $update    = mysqli_prepare($conn,
                "UPDATE user SET login_attempts = ? WHERE username = ?"
            );
            mysqli_stmt_bind_param($update, "is", $attempts, $username);
            mysqli_stmt_execute($update);

            // Deliberately vague error message - does not reveal whether
            // the username or password was wrong. If we said "username not found"
            // separately an attacker could use that to confirm valid usernames exist
            $_SESSION['error'] = "Invalid username or password. $remaining attempt(s) remaining.";
        }

        header("Location: login.php");
        exit();
    }

} else {
    // Username not found in DB
    // Same vague error message as wrong password - intentional security practice
    // Prevents attackers from using different messages to confirm valid usernames
    $_SESSION['error'] = "Invalid username or password.";
    header("Location: login.php");
    exit();
}

// Explicitly close DB connection to free resources
// PHP closes it automatically at end of script anyway
// but being explicit shows good coding practice
mysqli_close($conn);
?>