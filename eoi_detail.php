<?php
/*
 * eoi_detail.php - Full EOI Detail View
 * Read-only page showing complete information for one specific EOI.
 * Reached by clicking "View Details" on any row in manage.php.
 * The EOI number is passed as a GET parameter in the URL: eoi_detail.php?id=5
 * Protected - only accessible to logged in managers.
 */
session_start(); // Initialise session to check login status
include('settings.php'); // Load DB credentials from settings.php

// -------------------------------------------------------
// Security Gate - Authentication Check
// Same protection as manage.php - no session means not logged in
// Redirect immediately so sensitive applicant data is never exposed
// -------------------------------------------------------
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// -------------------------------------------------------
// Block Direct Access Without a Valid ID
// This page only makes sense when reached from manage.php
// with an EOI number in the URL e.g. eoi_detail.php?id=5
// If someone types eoi_detail.php with no id there is nothing to show
// || means if EITHER condition is true - no id at all OR id is empty
// -------------------------------------------------------
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage.php");
    exit();
}

// Connect to DB using credentials from settings.php
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch the specific EOI by its number from the URL
// 'i' in bind_param = integer type since EOInumber is INT in the DB
// Unlike manage.php which used fetch_all for multiple rows,
// fetch_assoc is used here since we only expect one record
$id   = $_GET['id'];
$stmt = mysqli_prepare($conn, "SELECT * FROM eoi WHERE EOInumber = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$eoi    = mysqli_fetch_assoc($result);

// Defensive programming - handle the edge case where someone manually
// types an ID that does not exist e.g. eoi_detail.php?id=999
// Rather than crashing on null data we redirect back to manage.php gracefully
if (!$eoi) {
    header("Location: manage.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Dynamic page title shows the actual EOI number in the browser tab
       e.g. "EOI #5 - EcoCity Co" - each detail page has a unique title -->
  <title>EOI #<?php echo htmlspecialchars($eoi['EOInumber']); ?> - EcoCity Co</title>
  <!-- External CSS - shares manage.css with manage.php for consistent portal styling -->
  <link rel="stylesheet" href="styles/manage.css">

  <!--
    Embedded CSS - detail page specific styles for the card layout,
    section headers, label-value rows, skill tags and status badge.
    These only apply to eoi_detail.php so they live here not in manage.css.
  -->
  <style>
    /* White card container for the full EOI details */
    .detail-card {
      background: #fff;
      border-radius: 8px;
      border: 1px solid #e0e0e0;
      padding: 2rem;
      max-width: 700px;
    }

    /* Each logical group of fields e.g. Personal Details, Contact Details */
    .detail-section { margin-bottom: 1.5rem; }

    /* Section heading - small uppercase label with bottom border separator */
    .detail-section h3 {
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #888;
      margin-bottom: 0.75rem;
      border-bottom: 1px solid #f0f0f0;
      padding-bottom: 6px;
    }

    /* Each field displayed as a flex row - label on left, value on right */
    .detail-row {
      display: flex;
      gap: 1rem;
      margin-bottom: 8px;
      font-size: 14px;
    }

    /* Muted label text - min-width keeps all labels the same width for alignment */
    .detail-label { color: #888; min-width: 140px; }

    /* Bold value text */
    .detail-value { color: #333; font-weight: 500; }

    /* Skill tags container - flex with wrapping for multiple tags */
    .skill-tags { display: flex; flex-wrap: wrap; gap: 6px; }

    /* Base skill tag style - pill shape */
    .skill-tag { padding: 4px 10px; border-radius: 2rem; font-size: 12px; }

    /* Green tag when skill_* column is 1 (checked) */
    .skill-yes { background: #e8f8f0; color: #1a7a4a; border: 1px solid #b2dfcc; }

    /* Grey tag when skill_* column is 0 (unchecked) */
    .skill-no  { background: #f4f5f7; color: #aaa; border: 1px solid #e0e0e0; }

    /* Back navigation link */
    .back-btn  {
      display: inline-block;
      margin-bottom: 1.5rem;
      color: #534AB7;
      text-decoration: none;
      font-size: 14px;
    }
    .back-btn:hover { text-decoration: underline; }

    /* Status badge - pill shape with colour per status type */
    .status-badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 2rem;
      font-size: 12px;
      font-weight: 500;
    }

    /* Blue for New, Green for Current, Yellow for Final */
    .status-new     { background: #e8f4fd; color: #1a6fa8; }
    .status-current { background: #e8f8f0; color: #1a7a4a; }
    .status-final   { background: #fef9e7; color: #9a7d0a; }
  </style>
</head>
<body>

<div class="portal-wrap">

  <!-- Top bar matches manage.php exactly for consistent portal experience
       Manager always sees their username and logout button on every portal page -->
  <div class="topbar">
    <div class="topbar-left">
      <img src="images/company_logo.png" alt="EcoCity Co Logo" class="topbar-logo">
      <span class="portal-title">HR Manager Portal</span>
    </div>
    <div class="topbar-right">
      <!-- Session username displayed - htmlspecialchars() prevents XSS -->
      <span class="welcome">
        Welcome back, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!
      </span>
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>
  </div>

  <div class="main-content">

    <!-- Back navigation - good UX practice to always provide a clear way back -->
    <a href="manage.php" class="back-btn">← Back to Manager Panel</a>

    <!-- Detail card containing all sections of the EOI -->
    <div class="detail-card">

      <!-- ===== Application Info Section ===== -->
      <div class="detail-section">
        <h3>Application Info</h3>

        <div class="detail-row">
          <span class="detail-label">EOI Number</span>
          <span class="detail-value"><?php echo htmlspecialchars($eoi['EOInumber']); ?></span>
        </div>

        <div class="detail-row">
          <span class="detail-label">Job Reference</span>
          <span class="detail-value"><?php echo htmlspecialchars($eoi['job_ref_num']); ?></span>
        </div>

        <div class="detail-row">
          <span class="detail-label">Status</span>
          <span class="detail-value">
            <!--
              strtolower() converts status to lowercase to build the CSS class
              e.g. "New" → "status-new" which applies the blue badge colour
              The display text uses the original casing from the DB
              Inline style on the span adds a subtle left margin for spacing
            -->
            <span
              class="status-badge status-<?php echo strtolower($eoi['status']); ?>"
              style="margin-left: 4px;"
            >
              <?php echo htmlspecialchars($eoi['status']); ?>
            </span>
          </span>
        </div>
      </div>

      <!-- ===== Personal Details Section ===== -->
      <div class="detail-section">
        <h3>Personal Details</h3>

        <div class="detail-row">
          <span class="detail-label">Full Name</span>
          <!--
            Concatenates first and last name with a space using PHP's . operator
            Both columns combined into one readable display value
            htmlspecialchars() applied to the whole combined string
          -->
          <span class="detail-value">
            <?php echo htmlspecialchars($eoi['first_name'] . ' ' . $eoi['last_name']); ?>
          </span>
        </div>

        <div class="detail-row">
          <span class="detail-label">Date of Birth</span>
          <span class="detail-value"><?php echo htmlspecialchars($eoi['dob']); ?></span>
        </div>

        <div class="detail-row">
          <span class="detail-label">Gender</span>
          <span class="detail-value"><?php echo htmlspecialchars($eoi['gender']); ?></span>
        </div>
      </div>

      <!-- ===== Contact Details Section ===== -->
      <div class="detail-section">
        <h3>Contact Details</h3>

        <div class="detail-row">
          <span class="detail-label">Email</span>
          <span class="detail-value"><?php echo htmlspecialchars($eoi['email']); ?></span>
        </div>

        <div class="detail-row">
          <span class="detail-label">Phone</span>
          <span class="detail-value"><?php echo htmlspecialchars($eoi['phone']); ?></span>
        </div>

        <div class="detail-row">
          <span class="detail-label">Address</span>
          <span class="detail-value"><?php echo htmlspecialchars($eoi['street_address']); ?></span>
        </div>

        <div class="detail-row">
          <span class="detail-label">Suburb/Town</span>
          <span class="detail-value"><?php echo htmlspecialchars($eoi['suburb_town']); ?></span>
        </div>

        <div class="detail-row">
          <span class="detail-label">State</span>
          <span class="detail-value"><?php echo htmlspecialchars($eoi['state']); ?></span>
        </div>

        <div class="detail-row">
          <span class="detail-label">Postcode</span>
          <span class="detail-value"><?php echo htmlspecialchars($eoi['postcode']); ?></span>
        </div>
      </div>

      <!-- ===== Skills Section =====
           Skills stored as tinyint(1) in DB - 0 or 1
           Ternary operator checks each skill value:
           1 (truthy) → skill-yes class = green tag
           0 (falsy)  → skill-no class  = grey tag
           This gives instant visual overview of applicant skills
           much cleaner than displaying raw 0s and 1s from the DB -->
      <div class="detail-section">
        <h3>Skills</h3>
        <div class="skill-tags">
          <span class="skill-tag <?php echo $eoi['skill_iot']       ? 'skill-yes' : 'skill-no'; ?>">IoT</span>
          <span class="skill-tag <?php echo $eoi['skill_data']      ? 'skill-yes' : 'skill-no'; ?>">Data</span>
          <span class="skill-tag <?php echo $eoi['skill_urban']     ? 'skill-yes' : 'skill-no'; ?>">Urban</span>
          <span class="skill-tag <?php echo $eoi['skill_renewable'] ? 'skill-yes' : 'skill-no'; ?>">Renewable</span>
          <span class="skill-tag <?php echo $eoi['skill_problem']   ? 'skill-yes' : 'skill-no'; ?>">Problem Solving</span>
          <span class="skill-tag <?php echo $eoi['skill_teamwork']  ? 'skill-yes' : 'skill-no'; ?>">Teamwork</span>
        </div>

        <!--
          Other skills is optional in the DB - can be NULL if applicant left it blank
          !empty() handles both NULL and empty string safely
          Only renders this row if there is actually something to display
        -->
        <?php if (!empty($eoi['other_skills'])): ?>
          <div class="detail-row" style="margin-top: 10px;">
            <span class="detail-label">Other Skills</span>
            <span class="detail-value"><?php echo htmlspecialchars($eoi['other_skills']); ?></span>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>

</body>
</html>