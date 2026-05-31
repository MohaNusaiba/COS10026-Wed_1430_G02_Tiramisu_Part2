<?php
// Include DB credentials from settings.php
require_once("settings.php");
 
// Block direct URL access - this page should only be reached by submitting apply.php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: apply.php");
    exit();
}
 
// Connect to the database
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
 
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
 
// Create eoi table if it does not already exist
// This ensures the table is always available without manual setup
$create_table_sql = "
CREATE TABLE IF NOT EXISTS eoi (
    EOInumber      INT          NOT NULL AUTO_INCREMENT,
    job_ref_num    VARCHAR(5)   NOT NULL,
    first_name     VARCHAR(20)  NOT NULL,
    last_name      VARCHAR(20)  NOT NULL,
    dob            VARCHAR(10)  NOT NULL,
    gender         VARCHAR(10)  NOT NULL,
    street_address VARCHAR(40)  NOT NULL,
    suburb_town    VARCHAR(40)  NOT NULL,
    state          VARCHAR(3)   NOT NULL,
    postcode       VARCHAR(4)   NOT NULL,
    email          VARCHAR(100) NOT NULL,
    phone          VARCHAR(12)  NOT NULL,
    skill_iot      TINYINT(1)   DEFAULT 0,
    skill_data     TINYINT(1)   DEFAULT 0,
    skill_urban    TINYINT(1)   DEFAULT 0,
    skill_renewable TINYINT(1)  DEFAULT 0,
    skill_problem  TINYINT(1)   DEFAULT 0,
    skill_teamwork TINYINT(1)   DEFAULT 0,
    other_skills   TEXT,
    status         VARCHAR(10)  DEFAULT 'New',
    PRIMARY KEY (EOInumber)
)";
 
if (!mysqli_query($conn, $create_table_sql)) {
    die("Error creating eoi table: " . mysqli_error($conn));
}
 
// -------------------------------------------------------
// Sanitise function - cleans each input field by:
// trim()             - removes accidental whitespace
// stripslashes()     - removes backslashes from some server configs
// htmlspecialchars() - converts special chars to safe HTML (XSS prevention)
// -------------------------------------------------------
function sanitise_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
 
// Sanitise all text inputs from the POST form
$job_ref_num    = sanitise_input($_POST["job_ref_num"]    ?? "");
$first_name     = sanitise_input($_POST["first_name"]     ?? "");
$last_name      = sanitise_input($_POST["last_name"]      ?? "");
$dob            = sanitise_input($_POST["dob"]            ?? "");
$gender         = sanitise_input($_POST["gender"]         ?? "");
$street_address = sanitise_input($_POST["street_address"] ?? "");
$suburb_town    = sanitise_input($_POST["suburb_town"]    ?? "");
$state          = sanitise_input($_POST["state"]          ?? "");
$postcode       = sanitise_input($_POST["postcode"]       ?? "");
$email          = sanitise_input($_POST["email"]          ?? "");
$phone          = sanitise_input($_POST["phone"]          ?? "");
$other_skills   = sanitise_input($_POST["other_skills"]   ?? "");
 
// Skills come as an array from checkboxes - default to empty array if none ticked
$skills = $_POST["skill"] ?? [];
 
// Convert each skill to 1 (checked) or 0 (unchecked) for DB storage as tinyint
$skill_iot       = in_array("iot",       $skills) ? 1 : 0;
$skill_data      = in_array("data",      $skills) ? 1 : 0;
$skill_urban     = in_array("urban",     $skills) ? 1 : 0;
$skill_renewable = in_array("renewable", $skills) ? 1 : 0;
$skill_problem   = in_array("problem",   $skills) ? 1 : 0;
$skill_teamwork  = in_array("teamwork",  $skills) ? 1 : 0;
 
// -------------------------------------------------------
// Server-side validation
// All validation done here since novalidate is on the form
// Errors collected into array and displayed together
// -------------------------------------------------------
$errors = [];
 
// Job reference - exactly 5 alphanumeric characters
if ($job_ref_num == "") {
    $errors[] = "Job reference number is required.";
} elseif (!preg_match("/^[A-Za-z0-9]{5}$/", $job_ref_num)) {
    $errors[] = "Job reference number must be exactly 5 letters or numbers.";
}
 
// First name - letters only, max 20 characters
if ($first_name == "") {
    $errors[] = "First name is required.";
} elseif (!preg_match("/^[A-Za-z]{1,20}$/", $first_name)) {
    $errors[] = "First name must contain letters only and be 20 characters or less.";
}
 
// Last name - letters only, max 20 characters
if ($last_name == "") {
    $errors[] = "Last name is required.";
} elseif (!preg_match("/^[A-Za-z]{1,20}$/", $last_name)) {
    $errors[] = "Last name must contain letters only and be 20 characters or less.";
}
 
// Date of birth - must match dd/mm/yyyy format
if ($dob == "") {
    $errors[] = "Date of birth is required.";
} elseif (!preg_match("/^\d{2}\/\d{2}\/\d{4}$/", $dob)) {
    $errors[] = "Date of birth must be in dd/mm/yyyy format.";
}
 
// Gender - one radio option must be selected
if ($gender == "") {
    $errors[] = "Gender is required.";
}
 
// Street address - required, max 40 characters matching DB VARCHAR(40)
if ($street_address == "") {
    $errors[] = "Street address is required.";
} elseif (strlen($street_address) > 40) {
    $errors[] = "Street address must be 40 characters or less.";
}
 
// Suburb/town - required, max 40 characters
if ($suburb_town == "") {
    $errors[] = "Suburb/town is required.";
} elseif (strlen($suburb_town) > 40) {
    $errors[] = "Suburb/town must be 40 characters or less.";
}
 
// State - must match one of the valid Australian states/territories
$valid_states = ["VIC", "NSW", "QLD", "NT", "WA", "SA", "TAS", "ACT"];
if ($state == "") {
    $errors[] = "State is required.";
} elseif (!in_array($state, $valid_states)) {
    $errors[] = "Please select a valid state.";
}
 
// Postcode - exactly 4 digits
if ($postcode == "") {
    $errors[] = "Postcode is required.";
} elseif (!preg_match("/^\d{4}$/", $postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
}
 
// Email - PHP filter_var handles proper format validation
if ($email == "") {
    $errors[] = "Email is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}
 
// Phone - 8 to 12 digits or spaces
if ($phone == "") {
    $errors[] = "Phone number is required.";
} elseif (!preg_match("/^[0-9 ]{8,12}$/", $phone)) {
    $errors[] = "Phone number must contain 8 to 12 digits or spaces.";
}
 
// Skills - at least one checkbox or other skills text required
if (
    $skill_iot       == 0 &&
    $skill_data      == 0 &&
    $skill_urban     == 0 &&
    $skill_renewable == 0 &&
    $skill_problem   == 0 &&
    $skill_teamwork  == 0 &&
    $other_skills    == ""
) {
    $errors[] = "Please select at least one skill or enter other skills.";
}
 
// -------------------------------------------------------
// Helper function to render the page shell with
// header.inc, embedded CSS, content, and footer.inc
// Keeps error and success pages consistent with site design
// -------------------------------------------------------
function render_page($title, $content) {
    global $pageCSS;
    $pageCSS = "";
    $GLOBALS['title'] = $title;
    $GLOBALS['description'] = "EOI Application Result";
    $GLOBALS['keywords'] = "application, EOI";
    $GLOBALS['author'] = "Ruby Telford, 105916092";
    include 'header.inc';
    // Embedded CSS - styles the result pages shown after form submission
    // No external CSS file needed since these are simple single-use pages
    echo '
    <style>
        /* Page background matching the apply page gradient */
        body {
            background: linear-gradient(135deg, #f0faf0 0%, #e8f5e9 50%, #f5fbff 100%);
            min-height: 100vh;
        }
 
        /* Centers the result card on the page */
        .result-wrap {
            display: flex;
            justify-content: center;
            padding: 3rem 1rem;
        }
 
        /* White card container for the result message */
        .result-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(5, 54, 143, 0.10);
            padding: 2.5rem;
            max-width: 580px;
            width: 100%;
            border: 1px solid #e0eaff;
            text-align: center;
        }
 
        /* Icon circle above the heading */
        .result-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto;
            font-size: 2rem;
        }
 
        /* Green circle for success state */
        .result-icon.success {
            background: #e8f8f0;
            color: #1a7a4a;
            border: 2px solid #b2dfcc;
        }
 
        /* Red circle for error state */
        .result-icon.error {
            background: #fff0f0;
            color: #c0392b;
            border: 2px solid #f5c6c6;
        }
 
        /* Main result heading */
        .result-card h1 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #1a2e5a;
        }
 
        /* Subtext below heading */
        .result-card p {
            color: #475569;
            font-size: 0.95rem;
            margin-bottom: 0.75rem;
            line-height: 1.6;
        }
 
        /* EOI number highlight box */
        .eoi-highlight {
            background: #f0faf5;
            border: 2px solid #22c55e;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
            display: inline-block;
        }
 
        /* Error list styling */
        .error-list {
            text-align: left;
            background: #fff0f0;
            border: 1px solid #f5c6c6;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
        }
 
        .error-list li {
            color: #c0392b;
            font-size: 0.9rem;
            margin-bottom: 6px;
            line-height: 1.5;
        }
 
        /* Navigation links at bottom of card */
        .result-links {
            margin-top: 1.5rem;
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
 
        .result-links a {
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s ease;
        }
 
        /* Primary link - blue */
        .result-links a.primary {
            background: #05368f;
            color: #ffffff;
        }
 
        .result-links a.primary:hover {
            background: #0a4dbf;
        }
 
        /* Secondary link - grey outline */
        .result-links a.secondary {
            background: #f1f5f9;
            color: #475569;
            border: 1.5px solid #cbd5e1;
        }
 
        .result-links a.secondary:hover {
            background: #e2e8f0;
        }
    </style>
    ';
    echo '<main class="result-wrap"><div class="result-card">' . $content . '</div></main>';
    include 'footer.inc';
}
 
// -------------------------------------------------------
// Display errors if validation failed
// All errors shown at once so user knows every issue
// -------------------------------------------------------
if (count($errors) > 0) {
    $error_items = "";
    foreach ($errors as $error) {
        $error_items .= "<li>" . htmlspecialchars($error) . "</li>";
    }
 
    $content = '
        <!-- Inline style on icon applies the error red colour -->
        <div class="result-icon error" style="font-size: 2.5rem;">✗</div>
        <h1>Application Error</h1>
        <p>Please fix the following errors and resubmit your application.</p>
        <ul class="error-list">' . $error_items . '</ul>
        <div class="result-links">
            <a href="apply.php" class="primary">Return to Form</a>
        </div>
    ';
    render_page("Application Error - EcoCity Co.", $content);
    exit();
}
 
// -------------------------------------------------------
// Insert EOI into database using a prepared statement
// Prepared statements prevent SQL injection by separating
// SQL structure from user supplied data
// -------------------------------------------------------
$stmt = mysqli_prepare($conn, "INSERT INTO eoi (
    job_ref_num,
    first_name,
    last_name,
    dob,
    gender,
    street_address,
    suburb_town,
    state,
    postcode,
    email,
    phone,
    skill_iot,
    skill_data,
    skill_urban,
    skill_renewable,
    skill_problem,
    skill_teamwork,
    other_skills
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
 
// Bind parameters - s=string i=integer
// 11 strings for text fields, 6 integers for skill tinyints, 1 string for other_skills
mysqli_stmt_bind_param(
    $stmt,
    "sssssssssssiiiiiis",
    $job_ref_num,
    $first_name,
    $last_name,
    $dob,
    $gender,
    $street_address,
    $suburb_town,
    $state,
    $postcode,
    $email,
    $phone,
    $skill_iot,
    $skill_data,
    $skill_urban,
    $skill_renewable,
    $skill_problem,
    $skill_teamwork,
    $other_skills
);
 
if (mysqli_stmt_execute($stmt)) {
    // Get the auto generated EOI number assigned by AUTO_INCREMENT
    $eoi_number = mysqli_insert_id($conn);
 
    $content = '
        <!-- Inline style on icon applies the success green colour -->
        <div class="result-icon success" style="font-size: 2.5rem;">✓</div>
        <h1>Application Submitted!</h1>
        <p>Thank you for applying to EcoCity Co. We have received your expression of interest.</p>
 
        <!-- EOI number highlighted so applicant can note it down -->
        <div class="eoi-highlight">
            <p style="margin:0; font-size:0.85rem; color:#475569;">Your EOI Number</p>
            <p style="margin:0; font-size:2rem; font-weight:700; color:#05368f;">#' . htmlspecialchars($eoi_number) . '</p>
        </div>
 
        <p>Your application status is currently <strong>New</strong>. Please keep your EOI number for future reference.</p>
        <div class="result-links">
            <a href="index.php" class="primary">Return to Home</a>
            <a href="jobs.php" class="secondary">View More Jobs</a>
        </div>
    ';
    render_page("Application Submitted - EcoCity Co.", $content);
 
} else {
    $content = '
        <div class="result-icon error" style="font-size: 2.5rem;">✗</div>
        <h1>Something Went Wrong</h1>
        <p>We were unable to submit your application. Please try again.</p>
        <div class="result-links">
            <a href="apply.php" class="primary">Return to Form</a>
        </div>
    ';
    render_page("Application Error - EcoCity Co.", $content);
}
 
// Close statement and DB connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>