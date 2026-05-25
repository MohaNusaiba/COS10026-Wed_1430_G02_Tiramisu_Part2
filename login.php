<?php
session_start();

// If already logged in, go straight to manage.php
if (isset($_SESSION['username'])) {
    header("Location: manage.php");
    exit();
}

$error = "";
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

$title       = "Staff Login - EcoCity Co";
$description = "HR Manager Login";
$keywords    = "login, staff, manager";
$author      = "Nusaiba Mohammed, 104649533";
$pageCSS     = "styles/styles.css";
?>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1fce3;
    font-family: sans-serif;
    }

    .login-wrap {
    display: flex;
    width: 800px;
    min-height: 480px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(0,0,0,0.1);
    }

    /* Left purple panel */
    .login-left {
    flex: 1;
    background: #05368f;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    color: #EEEDFE;
    text-align: center;
    }

    .login-left p { 
    font-size: 18px;
    color: #CECBF6; 
    line-height: 1.6;
    margin-top: 1rem;
    }

    .login-left img {
    width: 350px;
    height: 350px;
    object-fit: contain;  /* keeps aspect ratio, no cropping */
    }

    /* Right white panel */
    .login-right {
    flex: 1.1;
    background: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 2.5rem;
    }

    h1 { 
    font-size: 24px; 
    font-weight: 500;
    margin-bottom: 4px;
    color: #222;
    }

    .sub { 
    font-size: 14px; 
    color: #888; 
    margin-bottom: 2rem; 
    }

    .back-btn {
    font-size: 13px;
    color: #05368f;
    text-decoration: none;
    margin-bottom: 1.5rem;
    display: inline-block;
    }

    .field-wrap { margin-bottom: 1.25rem; }

    .field-wrap label {
    display: block;
    font-size: 13px;
    color: #555;
    margin-bottom: 6px;
    }

    .field-wrap input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    }

    .field-wrap input:focus {
    outline: none;
    border-color: #534AB7;
    }

    .login-btn {
    width: 100%;
    padding: 11px;
    background: #05368f;
    color: #fff;
    border: none;
    border-radius: 2rem;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    margin-top: 0.5rem;
    }

    .login-btn:hover { background: #3C3489; }

    .error-msg {
    background: #fff0f0;
    color: #c0392b;
    font-size: 13px;
    padding: 10px 12px;
    border-radius: 8px;
    margin-bottom: 1rem;
    border: 1px solid #f5c6c6;
    }
</style>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Login - EcoCity Co</title>
  <link rel="stylesheet" href="styles/login.css">
</head>
<body>

<div class="login-wrap">

  <div class="login-left">
    <div class="brand">
      <img src="images/companylogo.png" alt="EcoCity Co. Logo">
    </div>
     <p><em><strong>Staff access only.<br>Authorised personnel please log in. </strong></em></p>
  </div>

  <div class="login-right">
    <a href="index.php" class="back-btn">← Back to site</a>

    <h1>Welcome back</h1>
    <p class="sub">HR Manager Portal</p>

    <?php if (!empty($error)): ?>
      <div class="error-msg">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <form method="post" action="process_login.php">
      <div class="field-wrap">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" 
               placeholder="Enter username" required>
      </div>

      <div class="field-wrap">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" 
               placeholder="Enter password" required>
      </div>

      <button type="submit" class="login-btn">Login</button>
    </form>
  </div>

</div>
</body>
</html>
