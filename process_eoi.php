<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("settings.php");
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: apply.php");
    exit();
}
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

function sanitise_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$create_table_sql = "
CREATE TABLE IF NOT EXISTS eoi (
    EOInumber INT NOT NULL AUTO_INCREMENT,
    job_ref_num VARCHAR(5) NOT NULL,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    dob VARCHAR(10) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    street_address VARCHAR(40) NOT NULL,
    suburb_town VARCHAR(40) NOT NULL,
    state VARCHAR(3) NOT NULL,
    postcode VARCHAR(4) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(12) NOT NULL,
    skill_iot TINYINT(1) DEFAULT 0,
    skill_data TINYINT(1) DEFAULT 0,
    skill_urban TINYINT(1) DEFAULT 0,
    skill_renewable TINYINT(1) DEFAULT 0,
    skill_problem TINYINT(1) DEFAULT 0,
    skill_teamwork TINYINT(1) DEFAULT 0,
    other_skills TEXT,
    status VARCHAR(10) DEFAULT 'New',
    PRIMARY KEY (EOInumber)
)";

if (!mysqli_query($conn, $create_table_sql)) {
    die("Error creating eoi table: " . mysqli_error($conn));
}

$job_ref_num = sanitise_input($_POST["job_ref_num"] ?? "");
$first_name = sanitise_input($_POST["first_name"] ?? "");
$last_name = sanitise_input($_POST["last_name"] ?? "");
$dob = sanitise_input($_POST["dob"] ?? "");
$gender = sanitise_input($_POST["gender"] ?? "");
$street_address = sanitise_input($_POST["street_address"] ?? "");
$suburb_town = sanitise_input($_POST["suburb_town"] ?? "");
$state = sanitise_input($_POST["state"] ?? "");
$postcode = sanitise_input($_POST["postcode"] ?? "");
$email = sanitise_input($_POST["email"] ?? "");
$phone = sanitise_input($_POST["phone"] ?? "");
$other_skills = sanitise_input($_POST["other_skills"] ?? "");

$skills = $_POST["skill"] ?? [];

$skill_iot = in_array("iot", $skills) ? 1 : 0;
$skill_data = in_array("data", $skills) ? 1 : 0;
$skill_urban = in_array("urban", $skills) ? 1 : 0;
$skill_renewable = in_array("renewable", $skills) ? 1 : 0;
$skill_problem = in_array("problem", $skills) ? 1 : 0;
$skill_teamwork = in_array("teamwork", $skills) ? 1 : 0;

$errors = [];

if ($job_ref_num == "") {
    $errors[] = "Job reference number is required.";
} elseif (!preg_match("/^[A-Za-z0-9]{5}$/", $job_ref_num)) {
    $errors[] = "Job reference number must be exactly 5 letters or numbers.";
}

if ($first_name == "") {
    $errors[] = "First name is required.";
} elseif (!preg_match("/^[A-Za-z]{1,20}$/", $first_name)) {
    $errors[] = "First name must contain letters only and be 20 characters or less.";
}

if ($last_name == "") {
    $errors[] = "Last name is required.";
} elseif (!preg_match("/^[A-Za-z]{1,20}$/", $last_name)) {
    $errors[] = "Last name must contain letters only and be 20 characters or less.";
}

if ($dob == "") {
    $errors[] = "Date of birth is required.";
} elseif (!preg_match("/^\d{2}\/\d{2}\/\d{4}$/", $dob)) {
    $errors[] = "Date of birth must be in dd/mm/yyyy format.";
}

if ($gender == "") {
    $errors[] = "Gender is required.";
}

if ($street_address == "") {
    $errors[] = "Street address is required.";
} elseif (strlen($street_address) > 40) {
    $errors[] = "Street address must be 40 characters or less.";
}

if ($suburb_town == "") {
    $errors[] = "Suburb/town is required.";
} elseif (strlen($suburb_town) > 40) {
    $errors[] = "Suburb/town must be 40 characters or less.";
}

$valid_states = ["VIC", "NSW", "QLD", "NT", "WA", "SA", "TAS", "ACT"];

if ($state == "") {
    $errors[] = "State is required.";
} elseif (!in_array($state, $valid_states)) {
    $errors[] = "Please select a valid state.";
}

if ($postcode == "") {
    $errors[] = "Postcode is required.";
} elseif (!preg_match("/^\d{4}$/", $postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
}

if ($email == "") {
    $errors[] = "Email is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

if ($phone == "") {
    $errors[] = "Phone number is required.";
} elseif (!preg_match("/^[0-9 ]{8,12}$/", $phone)) {
    $errors[] = "Phone number must contain 8 to 12 digits or spaces.";
}

if (
    $skill_iot == 0 &&
    $skill_data == 0 &&
    $skill_urban == 0 &&
    $skill_renewable == 0 &&
    $skill_problem == 0 &&
    $skill_teamwork == 0 &&
    $other_skills == ""
) {
    $errors[] = "Please select at least one skill or enter other skills.";
}

if (count($errors) > 0) {
    echo "<h1>Application Error</h1>";
    echo "<p>Please fix the following errors:</p>";
    echo "<ul>";

    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }

    echo "</ul>";
    echo "<p><a href='apply.php'>Return to application form</a></p>";
    exit();
}

$query = "INSERT INTO eoi (
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
) VALUES (
    '$job_ref_num',
    '$first_name',
    '$last_name',
    '$dob',
    '$gender',
    '$street_address',
    '$suburb_town',
    '$state',
    '$postcode',
    '$email',
    '$phone',
    '$skill_iot',
    '$skill_data',
    '$skill_urban',
    '$skill_renewable',
    '$skill_problem',
    '$skill_teamwork',
    '$other_skills'
)";

$result = mysqli_query($conn, $query);

if ($result) {
    $eoi_number = mysqli_insert_id($conn);

    echo "<h1>Application submitted successfully</h1>";
    echo "<p>Your EOI number is: $eoi_number</p>";
    echo "<p>Your application status is: New</p>";
    echo "<p><a href='index.php'>Return to Home</a></p>";
} else {
    echo "<h1>Something went wrong</h1>";
    echo "<p>" . mysqli_error($conn) . "</p>";
}

mysqli_close($conn);
?>