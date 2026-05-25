<?php
session_start();
include('settings.php');
 
// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
 
// Session timeout - 30 mins (change to 180 for demo)
$timeout = 1800;
if (isset($_SESSION['last_activity']) && 
    time() - $_SESSION['last_activity'] > $timeout) {
    session_destroy();
    $_SESSION['error'] = "Session expired. Please log in again.";
    header("Location: login.php");
    exit();
}
$_SESSION['last_activity'] = time();
 
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
 
// Handle status update
if (isset($_POST['update_status'])) {
    $eoi_id  = $_POST['eoi_id'];
    $status  = $_POST['status'];
    $stmt = mysqli_prepare($conn, "UPDATE eoi SET status = ? WHERE EOInumber = ?");
    mysqli_stmt_bind_param($stmt, "si", $status, $eoi_id);
    mysqli_stmt_execute($stmt);
}
 
// Handle delete by job reference
if (isset($_POST['delete_ref'])) {
    $del_ref = trim($_POST['del_ref']);
    $stmt = mysqli_prepare($conn, "DELETE FROM eoi WHERE job_ref_num = ?");
    mysqli_stmt_bind_param($stmt, "s", $del_ref);
    mysqli_stmt_execute($stmt);
    $delete_msg = "All EOIs for job reference '" . htmlspecialchars($del_ref) . "' have been deleted.";
}
 
// Build query based on filters
$search_type  = $_POST['search_type']  ?? 'all';
$search_value = trim($_POST['search_value'] ?? '');
$first_name   = trim($_POST['first_name']   ?? '');
$last_name    = trim($_POST['last_name']    ?? '');
$sort_field   = $_POST['sort_field']   ?? 'EOInumber';
$sort_order   = $_POST['sort_order']   ?? 'ASC';
 
// Whitelist sort fields for security
$allowed_sorts = ['EOInumber', 'job_ref_num', 'first_name', 'last_name', 'status'];
if (!in_array($sort_field, $allowed_sorts)) {
    $sort_field = 'EOInumber';
}
$sort_order = $sort_order === 'DESC' ? 'DESC' : 'ASC';
 
// Build query
if ($search_type === 'job_ref' && !empty($search_value)) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM eoi WHERE job_ref_num = ? ORDER BY $sort_field $sort_order");
    mysqli_stmt_bind_param($stmt, "s", $search_value);
} elseif ($search_type === 'name') {
    if (!empty($first_name) && !empty($last_name)) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM eoi WHERE first_name = ? AND last_name = ? ORDER BY $sort_field $sort_order");
        mysqli_stmt_bind_param($stmt, "ss", $first_name, $last_name);
    } elseif (!empty($first_name)) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM eoi WHERE first_name = ? ORDER BY $sort_field $sort_order");
        mysqli_stmt_bind_param($stmt, "s", $first_name);
    } elseif (!empty($last_name)) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM eoi WHERE last_name = ? ORDER BY $sort_field $sort_order");
        mysqli_stmt_bind_param($stmt, "s", $last_name);
    } else {
        $stmt = mysqli_prepare($conn, "SELECT * FROM eoi ORDER BY $sort_field $sort_order");
    }
} else {
    $stmt = mysqli_prepare($conn, "SELECT * FROM eoi ORDER BY $sort_field $sort_order");
}
 
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$eois   = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manager Portal - EcoCity Co</title>
  <link rel="stylesheet" href="styles/manage.css">
</head>
<body>
 
<div class="portal-wrap">
 
  <!-- Top Bar -->
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
 
  <div class="portal-body">
 
    <!-- Sidebar -->
    <div class="sidebar">
      <p class="sidebar-label">Queries</p>
 
      <form method="post" action="manage.php">
        <!-- List All -->
        <button type="submit" name="search_type" value="all" class="sidebar-btn">
          List All EOIs
        </button>
      </form>
 
      <!-- Search by Job Ref -->
      <form method="post" action="manage.php">
        <input type="hidden" name="search_type" value="job_ref">
        <p class="sidebar-label" style="margin-top:1.5rem;">Search by Job Ref</p>
        <input type="text" name="search_value" placeholder="e.g. SC123" class="sidebar-input">
        <button type="submit" class="sidebar-btn">Search</button>
      </form>
 
      <!-- Search by Name -->
      <form method="post" action="manage.php">
        <input type="hidden" name="search_type" value="name">
        <p class="sidebar-label" style="margin-top:1.5rem;">Search by Name</p>
        <input type="text" name="first_name" placeholder="First name" class="sidebar-input">
        <input type="text" name="last_name" placeholder="Last name" class="sidebar-input">
        <button type="submit" class="sidebar-btn">Search</button>
      </form>
 
      <!-- Delete by Job Ref -->
      <form method="post" action="manage.php" 
            onsubmit="return confirm('Delete ALL EOIs for this job reference? This cannot be undone!');">
        <p class="sidebar-label" style="margin-top:1.5rem; color:#c0392b;">Delete by Job Ref</p>
        <input type="text" name="del_ref" placeholder="e.g. SC123" class="sidebar-input">
        <button type="submit" name="delete_ref" class="sidebar-btn delete-btn">Delete</button>
      </form>
 
      <!-- Sort Options -->
      <form method="post" action="manage.php">
        <input type="hidden" name="search_type" value="<?php echo htmlspecialchars($search_type); ?>">
        <input type="hidden" name="search_value" value="<?php echo htmlspecialchars($search_value); ?>">
        <input type="hidden" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>">
        <input type="hidden" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
        <p class="sidebar-label" style="margin-top:1.5rem;">Sort Results</p>
        <select name="sort_field" class="sidebar-input">
          <option value="EOInumber"  <?php echo $sort_field === 'EOInumber'  ? 'selected' : ''; ?>>EOI Number</option>
          <option value="job_ref_num"<?php echo $sort_field === 'job_ref_num'? 'selected' : ''; ?>>Job Reference</option>
          <option value="first_name" <?php echo $sort_field === 'first_name' ? 'selected' : ''; ?>>First Name</option>
          <option value="last_name"  <?php echo $sort_field === 'last_name'  ? 'selected' : ''; ?>>Last Name</option>
          <option value="status"     <?php echo $sort_field === 'status'     ? 'selected' : ''; ?>>Status</option>
        </select>
        <select name="sort_order" class="sidebar-input">
          <option value="ASC"  <?php echo $sort_order === 'ASC'  ? 'selected' : ''; ?>>Ascending</option>
          <option value="DESC" <?php echo $sort_order === 'DESC' ? 'selected' : ''; ?>>Descending</option>
        </select>
        <button type="submit" class="sidebar-btn">Sort</button>
      </form>
 
    </div>
 
    <!-- Main Content -->
    <div class="main-content">
 
      <?php if (isset($delete_msg)): ?>
        <div class="alert-msg"><?php echo $delete_msg; ?></div>
      <?php endif; ?>
 
      <div class="results-header">
        <h2>EOI Results <span class="count"><?php echo count($eois); ?> record(s)</span></h2>
      </div>
 
      <?php if (empty($eois)): ?>
        <p class="no-results">No EOIs found.</p>
      <?php else: ?>
        <div class="table-wrap">
          <table class="eoi-table">
            <thead>
              <tr>
                <th>EOI #</th>
                <th>Job Ref</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($eois as $eoi): ?>
              <tr>
                <td><?php echo htmlspecialchars($eoi['EOInumber']); ?></td>
                <td><?php echo htmlspecialchars($eoi['job_ref_num']); ?></td>
                <td><?php echo htmlspecialchars($eoi['first_name']); ?></td>
                <td><?php echo htmlspecialchars($eoi['last_name']); ?></td>
                <td><?php echo htmlspecialchars($eoi['email']); ?></td>
                <td>
                  <!-- Status update form per row -->
                  <form method="post" action="manage.php" style="display:inline;">
                    <input type="hidden" name="eoi_id" value="<?php echo $eoi['EOInumber']; ?>">
                    <input type="hidden" name="search_type" value="<?php echo htmlspecialchars($search_type); ?>">
                    <input type="hidden" name="sort_field" value="<?php echo htmlspecialchars($sort_field); ?>">
                    <input type="hidden" name="sort_order" value="<?php echo htmlspecialchars($sort_order); ?>">
                    <select name="status" class="status-select <?php echo strtolower($eoi['status']); ?>">
                      <option value="New"     <?php echo $eoi['status'] === 'New'     ? 'selected' : ''; ?>>New</option>
                      <option value="Current" <?php echo $eoi['status'] === 'Current' ? 'selected' : ''; ?>>Current</option>
                      <option value="Final"   <?php echo $eoi['status'] === 'Final'   ? 'selected' : ''; ?>>Final</option>
                    </select>
                    <button type="submit" name="update_status" class="update-btn">Update</button>
                  </form>
                </td>
                <td>
                  <a href="eoi_detail.php?id=<?php echo $eoi['EOInumber']; ?>" class="detail-btn">View Details</a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
 
</body>
</html>