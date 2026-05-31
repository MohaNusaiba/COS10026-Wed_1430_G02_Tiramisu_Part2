<?php
/*
 * manage.php - HR Manager Portal Dashboard
 * Protected page - only accessible to logged in managers.
 * Self-posting page: posts back to itself to handle all actions
 * and then re-renders the results in the same view.
 * Handles: list all, search by ref, search by name,
 *          delete by ref, change status, sort results.
 */
session_start(); // Must be first - needed to read $_SESSION['username'] and 'last_activity'
include('settings.php'); // Load $host, $user, $pwd, $sql_db for DB connection

// -------------------------------------------------------
// Security Gate 1 - Authentication Check
// If no username in session nobody is logged in
// Redirect to login.php immediately - even if someone knows the URL
// they cannot access this page without a valid session
// -------------------------------------------------------
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// -------------------------------------------------------
// Security Gate 2 - Session Timeout
// $timeout = 1800 = 30 minutes in seconds
// time() returns current Unix timestamp (seconds since 1970)
// Subtracting last_activity from now gives seconds of inactivity
// If that exceeds $timeout the session is destroyed and manager
// is redirected to login with an expiry message
// Change to 180 (3 mins) for demo purposes
// -------------------------------------------------------
$timeout = 1800;
if (isset($_SESSION['last_activity']) &&
    time() - $_SESSION['last_activity'] > $timeout) {
    session_destroy();
    $_SESSION['error'] = "Session expired. Please log in again.";
    header("Location: login.php");
    exit();
}
// Reset last_activity on every page load so active managers
// are not timed out while they are still using the portal
$_SESSION['last_activity'] = time();

// Connect to DB using credentials from settings.php
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// -------------------------------------------------------
// Action: Update EOI Status
// Triggered when manager clicks Update button on a table row
// isset($_POST['update_status']) checks if that specific button was clicked
// since the page has multiple forms we need to identify which one submitted
// 'si' in bind_param: s = string (status), i = integer (EOInumber)
// -------------------------------------------------------
if (isset($_POST['update_status'])) {
    $eoi_id = $_POST['eoi_id'];
    $status = $_POST['status'];
    $stmt   = mysqli_prepare($conn, "UPDATE eoi SET status = ? WHERE EOInumber = ?");
    mysqli_stmt_bind_param($stmt, "si", $status, $eoi_id);
    mysqli_stmt_execute($stmt);
}

// -------------------------------------------------------
// Action: Delete All EOIs by Job Reference
// Triggered when manager submits the delete form
// DELETE removes every EOI row matching that job_ref_num
// Could be multiple rows if several applicants applied for the same job
// $delete_msg is set here and displayed in the main content area below
// -------------------------------------------------------
if (isset($_POST['delete_ref'])) {
    $del_ref = trim($_POST['del_ref']); // trim() removes accidental whitespace
    $stmt    = mysqli_prepare($conn, "DELETE FROM eoi WHERE job_ref_num = ?");
    mysqli_stmt_bind_param($stmt, "s", $del_ref);
    mysqli_stmt_execute($stmt);
    // htmlspecialchars() on the reference prevents XSS in the confirmation message
    $delete_msg = "All EOIs for job reference '" . htmlspecialchars($del_ref) . "' have been deleted.";
}

// -------------------------------------------------------
// Collect Filter and Sort Values
// ?? operator provides safe defaults if POST keys don't exist
// (e.g. on first page load before any form is submitted)
// trim() on text fields removes accidental whitespace
// -------------------------------------------------------
$search_type  = $_POST['search_type']  ?? 'all';
$search_value = trim($_POST['search_value'] ?? '');
$first_name   = trim($_POST['first_name']   ?? '');
$last_name    = trim($_POST['last_name']    ?? '');
$sort_field   = $_POST['sort_field']   ?? 'EOInumber';
$sort_order   = $_POST['sort_order']   ?? 'ASC';

// -------------------------------------------------------
// Security: Sort Field Whitelist
// We cannot use prepared statement placeholders for ORDER BY column names
// only for values - so we validate $sort_field against a whitelist instead
// Any value not in the allowed list is replaced with the safe default
// This prevents SQL injection through the sort field parameter
// -------------------------------------------------------
$allowed_sorts = ['EOInumber', 'job_ref_num', 'first_name', 'last_name', 'status'];
if (!in_array($sort_field, $allowed_sorts)) {
    $sort_field = 'EOInumber'; // Fall back to safe default if unexpected value received
}
// Only accept exactly 'DESC' - anything else defaults to 'ASC'
$sort_order = $sort_order === 'DESC' ? 'DESC' : 'ASC';

// -------------------------------------------------------
// Query Builder
// Builds different SQL depending on what the manager is searching for
// All user input values still use ? placeholders for SQL injection prevention
// $sort_field and $sort_order are safe because they passed the whitelist above
// -------------------------------------------------------
if ($search_type === 'job_ref' && !empty($search_value)) {
    // Filter by exact job reference number
    $stmt = mysqli_prepare($conn,
        "SELECT * FROM eoi WHERE job_ref_num = ? ORDER BY $sort_field $sort_order");
    mysqli_stmt_bind_param($stmt, "s", $search_value);

} elseif ($search_type === 'name') {
    // Filter by name - supports three combinations as required by assignment:
    // both names, first name only, or last name only
    if (!empty($first_name) && !empty($last_name)) {
        $stmt = mysqli_prepare($conn,
            "SELECT * FROM eoi WHERE first_name = ? AND last_name = ? ORDER BY $sort_field $sort_order");
        mysqli_stmt_bind_param($stmt, "ss", $first_name, $last_name);
    } elseif (!empty($first_name)) {
        $stmt = mysqli_prepare($conn,
            "SELECT * FROM eoi WHERE first_name = ? ORDER BY $sort_field $sort_order");
        mysqli_stmt_bind_param($stmt, "s", $first_name);
    } elseif (!empty($last_name)) {
        $stmt = mysqli_prepare($conn,
            "SELECT * FROM eoi WHERE last_name = ? ORDER BY $sort_field $sort_order");
        mysqli_stmt_bind_param($stmt, "s", $last_name);
    } else {
        $stmt = mysqli_prepare($conn, "SELECT * FROM eoi ORDER BY $sort_field $sort_order");
    }

} else {
    // Default - list all EOIs (also used for 'List All EOIs' button)
    $stmt = mysqli_prepare($conn, "SELECT * FROM eoi ORDER BY $sort_field $sort_order");
}

// Execute whichever query was built above
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// fetch_all returns every matching row at once as associative arrays
$eois = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manager Portal - EcoCity Co</title>
  <!-- External CSS - manage.css handles portal layout, sidebar, table and button styles -->
  <link rel="stylesheet" href="styles/manage.css">
</head>
<body>

<div class="portal-wrap">

  <!-- ===== Top Bar =====
       Standalone portal header - no header.inc since manage.php
       is a separate staff portal not part of the public site.
       h1 used for the portal title to satisfy accessibility heading requirement -->
  <div class="topbar">
    <div class="topbar-left">
      <img src="images/companylogo.png" alt="EcoCity Co Logo" class="topbar-logo">
      <!-- h1 provides the first level heading required by WAVE accessibility check -->
      <h1 class="portal-title">HR Manager Portal</h1>
    </div>
    <div class="topbar-right">
      <!-- $_SESSION['username'] displays who is logged in
           htmlspecialchars() prevents XSS even on session data -->
      <span class="welcome">
        Welcome back, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!
      </span>
      <!-- Logout link - session_destroy() in logout.php wipes the session -->
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>
  </div>

  <div class="portal-body">

    <!-- ===== Sidebar =====
         Each query is its own separate form all posting back to manage.php
         This is the self-posting pattern - one page handles both display and actions
         isset($_POST['button_name']) in the PHP above identifies which form submitted
         aria-label added to all inputs to fix WAVE missing label accessibility errors -->
    <div class="sidebar">
      <p class="sidebar-label">Queries</p>

      <!-- List All EOIs - submits search_type=all which triggers the default query -->
      <form method="post" action="manage.php">
        <button type="submit" name="search_type" value="all" class="sidebar-btn">
          List All EOIs
        </button>
      </form>

      <!-- Search by Job Reference
           Hidden input carries search_type=job_ref so PHP knows which query to build
           aria-label provides accessible label for screen readers (no visible label used
           to preserve the compact sidebar layout) -->
      <form method="post" action="manage.php">
        <input type="hidden" name="search_type" value="job_ref">
        <p class="sidebar-label" style="margin-top:1.5rem;">Search by Job Ref</p>
        <input
          type="text"
          name="search_value"
          id="search_value"
          placeholder="e.g. SC123"
          class="sidebar-input"
          aria-label="Search by job reference number"
        >
        <button type="submit" class="sidebar-btn">Search</button>
      </form>

      <!-- Search by Name
           Supports first name only, last name only, or both
           PHP query builder handles all three combinations -->
      <form method="post" action="manage.php">
        <input type="hidden" name="search_type" value="name">
        <p class="sidebar-label" style="margin-top:1.5rem;">Search by Name</p>
        <input
          type="text"
          name="first_name"
          id="first_name"
          placeholder="First name"
          class="sidebar-input"
          aria-label="Search by applicant first name"
        >
        <input
          type="text"
          name="last_name"
          id="last_name"
          placeholder="Last name"
          class="sidebar-input"
          aria-label="Search by applicant last name"
        >
        <button type="submit" class="sidebar-btn">Search</button>
      </form>

      <!-- Delete by Job Reference
           onsubmit confirm() shows a browser dialog before submitting
           return confirm() cancels the form if manager clicks Cancel
           This prevents accidental deletion of records -->
      <form method="post" action="manage.php"
            onsubmit="return confirm('Delete ALL EOIs for this job reference? This cannot be undone!');">
        <p class="sidebar-label" style="margin-top:1.5rem; color:#c0392b;">Delete by Job Ref</p>
        <input
          type="text"
          name="del_ref"
          id="del_ref"
          placeholder="e.g. SC123"
          class="sidebar-input"
          aria-label="Job reference number to delete all EOIs for"
        >
        <button type="submit" name="delete_ref" class="sidebar-btn delete-btn">Delete</button>
      </form>

      <!-- Sort Options
           Hidden inputs preserve current search context so sorting
           does not reset the current search results back to all EOIs
           visually-hidden labels fix WAVE select missing label errors
           without affecting the visual layout of the sidebar -->
      <form method="post" action="manage.php">
        <input type="hidden" name="search_type"  value="<?php echo htmlspecialchars($search_type); ?>">
        <input type="hidden" name="search_value" value="<?php echo htmlspecialchars($search_value); ?>">
        <input type="hidden" name="first_name"   value="<?php echo htmlspecialchars($first_name); ?>">
        <input type="hidden" name="last_name"    value="<?php echo htmlspecialchars($last_name); ?>">
        <p class="sidebar-label" style="margin-top:1.5rem;">Sort Results</p>

        <!-- visually-hidden label is read by screen readers but invisible on screen -->
        <label for="sort_field" class="visually-hidden">Sort by field</label>
        <select name="sort_field" id="sort_field" class="sidebar-input">
          <option value="EOInumber"   <?php echo $sort_field === 'EOInumber'   ? 'selected' : ''; ?>>EOI Number</option>
          <option value="job_ref_num" <?php echo $sort_field === 'job_ref_num' ? 'selected' : ''; ?>>Job Reference</option>
          <option value="first_name"  <?php echo $sort_field === 'first_name'  ? 'selected' : ''; ?>>First Name</option>
          <option value="last_name"   <?php echo $sort_field === 'last_name'   ? 'selected' : ''; ?>>Last Name</option>
          <option value="status"      <?php echo $sort_field === 'status'      ? 'selected' : ''; ?>>Status</option>
        </select>

        <label for="sort_order" class="visually-hidden">Sort order</label>
        <select name="sort_order" id="sort_order" class="sidebar-input">
          <option value="ASC"  <?php echo $sort_order === 'ASC'  ? 'selected' : ''; ?>>Ascending</option>
          <option value="DESC" <?php echo $sort_order === 'DESC' ? 'selected' : ''; ?>>Descending</option>
        </select>
        <button type="submit" class="sidebar-btn">Sort</button>
      </form>

    </div>

    <!-- ===== Main Content Area =====
         Changed from div to main semantic tag to fix WAVE
         'No page regions' accessibility error -->
    <main class="main-content">

      <!-- Delete confirmation message - only rendered if $delete_msg was set above -->
      <?php if (isset($delete_msg)): ?>
        <div class="alert-msg"><?php echo $delete_msg; ?></div>
      <?php endif; ?>

      <!-- Results count header - count() counts elements in the $eois array -->
      <div class="results-header">
        <h2>EOI Results
          <span class="count"><?php echo count($eois); ?> record(s)</span>
        </h2>
      </div>

      <?php if (empty($eois)): ?>
        <!-- Shown when search returns no matching records -->
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
              <!--
                foreach loops through every EOI in the $eois array
                Each iteration renders one table row with that EOI's data
                htmlspecialchars() on every output - consistent XSS prevention
              -->
              <?php foreach ($eois as $eoi): ?>
              <tr>
                <td><?php echo htmlspecialchars($eoi['EOInumber']); ?></td>
                <td><?php echo htmlspecialchars($eoi['job_ref_num']); ?></td>
                <td><?php echo htmlspecialchars($eoi['first_name']); ?></td>
                <td><?php echo htmlspecialchars($eoi['last_name']); ?></td>
                <td><?php echo htmlspecialchars($eoi['email']); ?></td>
                <td>
                  <!--
                    Per-row status update form - each row has its own mini form
                    style="display:inline" prevents the form breaking to a new line
                    Hidden eoi_id carries this row's EOInumber so the UPDATE query
                    knows exactly which record to change
                    strtolower() on status builds the CSS class name e.g. "new"
                    Ternary operator pre-selects the current status in the dropdown
                  -->
                  <form method="post" action="manage.php" style="display:inline;">
                    <input type="hidden" name="eoi_id"      value="<?php echo $eoi['EOInumber']; ?>">
                    <input type="hidden" name="search_type" value="<?php echo htmlspecialchars($search_type); ?>">
                    <input type="hidden" name="sort_field"  value="<?php echo htmlspecialchars($sort_field); ?>">
                    <input type="hidden" name="sort_order"  value="<?php echo htmlspecialchars($sort_order); ?>">
                    <!-- aria-label on select fixes WAVE missing label error in table rows -->
                    <select
                      name="status"
                      class="status-select <?php echo strtolower($eoi['status']); ?>"
                      aria-label="Update status for EOI <?php echo htmlspecialchars($eoi['EOInumber']); ?>"
                    >
                      <option value="New"     <?php echo $eoi['status'] === 'New'     ? 'selected' : ''; ?>>New</option>
                      <option value="Current" <?php echo $eoi['status'] === 'Current' ? 'selected' : ''; ?>>Current</option>
                      <option value="Final"   <?php echo $eoi['status'] === 'Final'   ? 'selected' : ''; ?>>Final</option>
                    </select>
                    <button type="submit" name="update_status" class="update-btn">Update</button>
                  </form>
                </td>
                <td>
                  <!--
                    View Details link passes EOInumber as GET parameter in the URL
                    e.g. eoi_detail.php?id=5
                    eoi_detail.php reads this with $_GET['id'] to fetch the full record
                  -->
                  <a href="eoi_detail.php?id=<?php echo $eoi['EOInumber']; ?>" class="detail-btn">
                    View Details
                  </a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </main>
  </div>
</div>

</body>
</html>