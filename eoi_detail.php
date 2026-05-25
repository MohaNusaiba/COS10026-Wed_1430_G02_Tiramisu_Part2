<?php
session_start();
include('settings.php');
 
// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
 
// Block direct access without ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage.php");
    exit();
}
 
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
 
$id   = $_GET['id'];
$stmt = mysqli_prepare($conn, "SELECT * FROM eoi WHERE EOInumber = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$eoi    = mysqli_fetch_assoc($result);
 
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
  <title>EOI #<?php echo htmlspecialchars($eoi['EOInumber']); ?> - EcoCity Co</title>
  <link rel="stylesheet" href="styles/manage.css">
  <style>
    .detail-card { background: #fff; border-radius: 8px; border: 1px solid #e0e0e0; padding: 2rem; max-width: 700px; }
    .detail-section { margin-bottom: 1.5rem; }
    .detail-section h3 { font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; color: #888; margin-bottom: 0.75rem; border-bottom: 1px solid #f0f0f0; padding-bottom: 6px; }
    .detail-row { display: flex; gap: 1rem; margin-bottom: 8px; font-size: 14px; }
    .detail-label { color: #888; min-width: 140px; }
    .detail-value { color: #333; font-weight: 500; }
    .skill-tags { display: flex; flex-wrap: wrap; gap: 6px; }
    .skill-tag { padding: 4px 10px; border-radius: 2rem; font-size: 12px; }
    .skill-yes { background: #e8f8f0; color: #1a7a4a; border: 1px solid #b2dfcc; }
    .skill-no  { background: #f4f5f7; color: #aaa; border: 1px solid #e0e0e0; }
    .back-btn  { display: inline-block; margin-bottom: 1.5rem; color: #534AB7; text-decoration: none; font-size: 14px; }
    .back-btn:hover { text-decoration: underline; }
    .status-badge { display: inline-block; padding: 4px 12px; border-radius: 2rem; font-size: 12px; font-weight: 500; }
    .status-new     { background: #e8f4fd; color: #1a6fa8; }
    .status-current { background: #e8f8f0; color: #1a7a4a; }
    .status-final   { background: #fef9e7; color: #9a7d0a; }
  </style>
</head>
<body>
 
<div class="portal-wrap">
  <div class="topbar">
    <div class="topbar-left">
      <img src="images/companylogo.png" alt="EcoCity Co Logo" class="topbar-logo">
      <span class="portal-title">HR Manager Portal</span>
    </div>
    <div class="topbar-right">
      <span class="welcome">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</span>
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>
  </div>
 
  <div class="main-content">
    <a href="manage.php" class="back-btn">← Back to Manager Panel</a>
 
    <div class="detail-card">
 
      <div class="detail-section">
        <h3>Application Info</h3>
        <div class="detail-row"><span class="detail-label">EOI Number</span><span class="detail-value"><?php echo htmlspecialchars($eoi['EOInumber']); ?></span></div>
        <div class="detail-row"><span class="detail-label">Job Reference</span><span class="detail-value"><?php echo htmlspecialchars($eoi['job_ref_num']); ?></span></div>
        <div class="detail-row">
          <span class="detail-label">Status</span>
          <span class="detail-value">
            <span class="status-badge status-<?php echo strtolower($eoi['status']); ?>">
              <?php echo htmlspecialchars($eoi['status']); ?>
            </span>
          </span>
        </div>
      </div>
 
      <div class="detail-section">
        <h3>Personal Details</h3>
        <div class="detail-row"><span class="detail-label">Full Name</span><span class="detail-value"><?php echo htmlspecialchars($eoi['first_name'] . ' ' . $eoi['last_name']); ?></span></div>
        <div class="detail-row"><span class="detail-label">Date of Birth</span><span class="detail-value"><?php echo htmlspecialchars($eoi['dob']); ?></span></div>
        <div class="detail-row"><span class="detail-label">Gender</span><span class="detail-value"><?php echo htmlspecialchars($eoi['gender']); ?></span></div>
      </div>
 
      <div class="detail-section">
        <h3>Contact Details</h3>
        <div class="detail-row"><span class="detail-label">Email</span><span class="detail-value"><?php echo htmlspecialchars($eoi['email']); ?></span></div>
        <div class="detail-row"><span class="detail-label">Phone</span><span class="detail-value"><?php echo htmlspecialchars($eoi['phone']); ?></span></div>
        <div class="detail-row"><span class="detail-label">Address</span><span class="detail-value"><?php echo htmlspecialchars($eoi['street_address']); ?></span></div>
        <div class="detail-row"><span class="detail-label">Suburb/Town</span><span class="detail-value"><?php echo htmlspecialchars($eoi['suburb_town']); ?></span></div>
        <div class="detail-row"><span class="detail-label">State</span><span class="detail-value"><?php echo htmlspecialchars($eoi['state']); ?></span></div>
        <div class="detail-row"><span class="detail-label">Postcode</span><span class="detail-value"><?php echo htmlspecialchars($eoi['postcode']); ?></span></div>
      </div>
 
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