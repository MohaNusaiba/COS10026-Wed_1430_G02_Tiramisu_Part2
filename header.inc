<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="keywords" content="<?php echo $keywords; ?>">
    <meta name="author" content="<?php echo $author; ?>">
    <title><?php echo $title; ?></title>

    <!-- Base reset, fonts and body background - loaded on every page -->
    <link rel="stylesheet" href="styles/base.css">

    <!-- Shared header, nav and footer layout - loaded on every page -->
    <link rel="stylesheet" href="styles/layout.css">

    <!-- Shared UI components like tables and tags - loaded on every page -->
    <link rel="stylesheet" href="styles/components.css">

    <!-- Page specific CSS - set via $pageCSS variable in each page's PHP block -->
    <?php if (!empty($pageCSS)) echo "<link rel='stylesheet' href='$pageCSS'>"; ?>

  </head>
  <body>
    <header>
      <nav class="menu">
        <!-- Logo Navbar: Following real-world practice even if WAVE says redundant links -->
        <a href="index.php" id="logo-link">
          <img src="images/header_logo.png" alt="EcoCity Co. Logo">
        </a>

        <!-- Navbar Links to pages container -->
        <div class="navlinks">
          <a href="index.php" class="navlink">Home</a>
          <a href="about.php" class="navlink">G02 Team</a>
          <a href="jobs.php" class="navlink">Careers</a>
          <a href="apply.php" class="navlink">Apply Now</a>
        </div>
      </nav>
    </header>