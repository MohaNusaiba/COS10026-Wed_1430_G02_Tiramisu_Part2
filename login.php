<?php
/*
 * login.php - Staff Login Page
 * Standalone portal page - does not use header.inc or footer.inc
 * since it has its own unique full-page layout separate from the public site.
 * Only accessible to HR managers - public visitors use the main site navigation.
 */
session_start(); // Must be called before any output - initialises the session system

// If a session already exists the manager is already logged in
// No point showing the login form - redirect straight to manage.php
if (isset($_SESSION['username'])) {
    header("Location: manage.php");
    exit(); // Always exit after header() to stop further code execution
}

// Check if a previous failed login attempt left an error in the session
// If so copy it into $error then immediately delete it with unset()
// so it only displays once and disappears on refresh (flash message pattern)
$error = "";
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

// Page variables set but not used here since login.php has no header.inc
// Kept for consistency with the rest of the site's page structure
$title       = "Staff Login - EcoCity Co";
$description = "HR Manager Login";
$keywords    = "login, staff, manager";
$author      = "Nusaiba Mohammed, 104649533";
$pageCSS     = "styles/login.css";
?>

<!--
  Embedded CSS - all login page styles live here since this is a
  standalone page with no external login.css file.
  The split panel layout (blue left, white right) is unique to this page.
-->
<style>
    /* Reset box model and spacing for clean layout */
    * { box-sizing: border-box; margin: 0; padding: 0; }

    /* Full viewport height - centers the login card vertically and horizontally
       soft green background matches the site's eco brand theme */
    body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1fce3;
        font-family: sans-serif;
    }

    /* Outer card container - fixed width with rounded corners and shadow
       overflow: hidden clips child elements to the border radius */
    .login-wrap {
        display: flex;
        width: 800px;
        min-height: 480px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0,0,0,0.1);
    }

    /* Left blue panel - brand colour with centered content
       flex:1 means it takes up equal space with the right panel */
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

    /* Descriptive text below the logo on the left panel */
    .login-left p {
        font-size: 18px;
        color: #CECBF6;
        line-height: 1.6;
        margin-top: 1rem;
    }

    /* Company logo - constrained to panel width
       object-fit: contain preserves aspect ratio without cropping */
    .login-left img {
        width: 350px;
        height: 350px;
        object-fit: contain;
    }

    /* Right white panel - slightly wider than left (flex: 1.1)
       to give the form more breathing room */
    .login-right {
        flex: 1.1;
        background: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 2.5rem;
    }

    /* Welcome heading on the right panel */
    h1 {
        font-size: 24px;
        font-weight: 500;
        margin-bottom: 4px;
        color: #222;
    }

    /* Subtitle below the heading */
    .sub {
        font-size: 14px;
        color: #888;
        margin-bottom: 2rem;
    }

    /* Back to site link - subtle blue matching brand colour
       display: inline-block allows margin to work correctly */
    .back-btn {
        font-size: 13px;
        color: #05368f;
        text-decoration: none;
        margin-bottom: 1.5rem;
        display: inline-block;
    }

    /* Wrapper for each label and input pair */
    .field-wrap { margin-bottom: 1.25rem; }

    /* Label above each input field */
    .field-wrap label {
        display: block;
        font-size: 13px;
        color: #555;
        margin-bottom: 6px;
    }

    /* Input fields - full width with consistent padding and border */
    .field-wrap input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
    }

    /* Focus state - removes default outline and replaces with brand colour border */
    .field-wrap input:focus {
        outline: none;
        border-color: #534AB7;
    }

    /* Login submit button - full width pill shape matching brand blue */
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

    /* Hover darkens the button slightly for visual feedback */
    .login-btn:hover { background: #3C3489; }

    /* Error message box - red toned background with border
       only rendered when $error is not empty */
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
  <!-- External CSS file for any additional login page overrides -->
  <link rel="stylesheet" href="styles/login.css">
</head>
<body>

<!-- Outer card wrapping both panels -->
<div class="login-wrap">

  <!-- Left blue panel - logo and access notice -->
  <div class="login-left">
    <div class="brand">
      <!-- Company logo - sized via embedded CSS above -->
      <img src="images/company_logo.png" alt="EcoCity Co. Logo">
    </div>
    <!-- Staff access notice - em and strong for semantic italic bold -->
    <p><em><strong>Staff access only.<br>Authorised personnel please log in.</strong></em></p>
  </div>

  <!-- Right white panel - back link, heading and login form -->
  <div class="login-right">

    <!-- Back link gives users an escape route if they landed here by accident
         Good UX practice - never trap users on a page with no way out -->
    <a href="index.php" class="back-btn">← Back to site</a>

    <h1>Welcome back</h1>
    <p class="sub">HR Manager Portal</p>

    <!--
      Error message block - only rendered when $error is not empty
      !empty() returns true if $error contains any text
      htmlspecialchars() converts special characters to safe HTML entities
      preventing XSS attacks even through the error message
    -->
    <?php if (!empty($error)): ?>
      <div class="error-msg">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <!--
      Login form - POST method keeps credentials out of the URL
      action sends data to process_login.php which handles all logic
      This page only shows the form - processing is intentionally separated
    -->
    <form method="post" action="process_login.php">

      <div class="field-wrap">
        <!-- label for="username" links to input id="username"
             clicking the label focuses the input - accessibility best practice -->
        <label for="username">Username</label>
        <!-- name="username" is what PHP reads as $_POST['username'] on the other side -->
        <input
          type="text"
          id="username"
          name="username"
          placeholder="Enter username"
          required
        >
      </div>

      <div class="field-wrap">
        <label for="password">Password</label>
        <!-- type="password" masks input with dots so credentials
             are not visible on screen if someone is watching -->
        <input
          type="password"
          id="password"
          name="password"
          placeholder="Enter password"
          required
        >
      </div>

      <!-- Inline CSS on button - draws attention as primary action
           gradient reinforces the brand blue colour scheme -->
      <button
        type="submit"
        class="login-btn"
        style="background: linear-gradient(135deg, #05368f, #0a4dbf);"
      >
        Login
      </button>

    </form>
  </div>

</div>
</body>
</html>